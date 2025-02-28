<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Colors\Rgb\Channels\Red;

class CartController extends Controller
{
    // ======================show cart page=====================//
    public function showCartPage(Request $request)
    {
        $id = $request->header('id');
        $cartProducts = ProductCart::where('user_id', $id)->with('products.specifications')->get();
        // return $cartProducts;dd();
        return Inertia::render('User/Cart/CartPage', [
            'cartProducts' => $cartProducts,
        ]);
    }

    //======================create cart=======================//
    public function createCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
        ]);

        // Retrieve product details
        $product = Product::find($request->product_id);

        // Check stock status
        if ($product->stock_status === 'out_of_stock') {
            return redirect()
                ->back()
                ->with(['message' => 'This product is out of stock and cannot be added to the cart.', 'status' => false]);
        }

        // Check if product is already in the cart
        $existingCartItem = ProductCart::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();

        if ($existingCartItem) {
            // Update existing quantity
            $newQty = $existingCartItem->qty + $request->qty;

            // Check if new quantity exceeds stock
            if ($newQty > $product->stock_quantity) {
                return redirect()
                    ->back()
                    ->with(['message' => 'Requested quantity exceeds available stock.', 'status' => false]);
            }

            // Update quantity and subtotal
            $existingCartItem->update([
                'qty' => $newQty,
                'subtotal' => $newQty * $request->price,
                'color' => $request->color,
                'size' => $request->size,
            ]);
        } else {
            // Add new product to cart
            ProductCart::create([
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
                'qty' => $request->qty,
                'price' => $request->price,
                'color' => $request->color,
                'size' => $request->size,
                'subtotal' => $request->qty * $request->price,
            ]);
        }

        return redirect()
            ->back()
            ->with([
                'message' => 'Product added to cart successfully. <a href="' . route('show.cart.page') . '" class="text-blue-500 underline">View Cart</a>',
                'status' => true,
            ]);
    }

    //====================cart delete=========================//
    public function deleteCart(Request $request, $id)
    {
        $user_id = $request->header('id');
        $deleteCart = ProductCart::where('user_id', $user_id)->where('id', $id)->first();
        $deleteCart->delete();
        return redirect()->back()->with('message', 'Cart Item deleted');
    }

    //update cart quantity and total in cart list page
    public function updateCart(Request $request, $id)
    {
        $user_id = $request->header('id');
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);
    
        // Find the cart item
        $cartItem = ProductCart::where('id', $id)->where('user_id', $user_id)->first();
    
        if (!$cartItem) {
            return redirect()->back()->with(['message' => 'Cart item not found', 'status' => false]);
        }
    
        // Get product stock from the products table
        $product = Product::where('id', $cartItem->product_id)->first();
    
        if (!$product) {
            return redirect()-> back() ->with(['message' => 'Product not found', 'status' => false]);
        }
    
        // Check if requested qty is more than available stock
        if ($request->qty > $product->stock_quantity) {
            return redirect()->back()->with(['message' => 'Not enough stock', 'status' => false]);
        }
    
        // Update quantity and subtotal
        $cartItem->qty = $request->qty;
        $cartItem->subtotal = $cartItem->qty * $cartItem->price;
        $cartItem->save();

        return redirect()->back()->with(['message' => 'Cart item updated successfully', 'status' => true]);
    }
    
}
