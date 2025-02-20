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
    public function showCartPage(){
        return Inertia::render('User/Cart/CartPage');
    }

    //======================create cart=======================//
    public function createCart(Request $request)
    {
        // return $request->all();dd();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id'    => 'required|exists:users,id',
            'qty'        => 'required|integer|min:1',
            'price'      => 'required|numeric',
            'color'      => 'nullable|string',
            'size'       => 'nullable|string',
        ]);
    
        // Retrieve product details
        $product = Product::find($request->product_id);
    
        // Check stock status
        if ($product->stock_status === 'out_of_stock') {
            $data = ['message' => 'This product is out of stock and cannot be added to the cart.', 'status' => false];
            return redirect()->back()->with($data);
        }
    
        // Check quantity availability
        if ($request->qty > $product->stock_quantity) {
            $data = ['message' => 'Requested quantity exceeds available stock.', 'status' => false];
            return redirect()->back()->with($data);
        }
    
        // Calculate subtotal
        $subtotal = $request->qty * $request->price;

        // Add product to cart
        ProductCart::updateOrCreate([
            'product_id' => $request->product_id,
            'user_id'    => $request->user_id,
            'qty'        => $request->qty,
            'price'      => $request->price,
            'color'      => $request->color,
            'size'       => $request->size,
            'subtotal'   => $subtotal,
        ]);

        $data = ['message' => 'Product added to cart successfully.', 'status' => true];
        return redirect()->back()->with($data);
    }

    //=====================cart item count======================//
    public function cartItemCount(Request $request){
        $user_id = $request->header('id');
        $cartItemCount = ProductCart::where('user_id', $user_id)->count();
        return $cartItemCount;
    }
    
}
