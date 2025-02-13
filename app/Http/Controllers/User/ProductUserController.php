<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductUserController extends Controller
{
        /**
     * =====================================
     *  User Product Controller
     * =====================================
     *  Handles user-related product operations.
     */

    //========================show product page========================//
    public function showProductPage()
    {
        $products = Product::get();
        $all_products = 'All Products';
        return Inertia::render('User/Product/ProductsPage',[
            'products' => $products,
            'all_products' => $all_products,
        ]);
    }

    //======================show product details page ======================//
    public function showProductDetailsPage(Request $request , $id = null)
    {
        // Ensure we get the correct product ID
        $id = $id ?? $request->query('id');

        if (!$id) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }

        // Fetch product details along with relationships
        $productDetails = Product::with(['product_details', 'specifications', 'productImages', 'category:id,name', 'brand:id,name'])->find($id);

        // return $productDetails;dd();

        // Check if the product exists
        if (!$productDetails) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return Inertia::render('User/Product/ProductDetailsPage',[
            'productDetails' => $productDetails,
        ]);
    }

    //======================get product by category =====================//
    public function productByCategory(Request $request, $url = null)
    {
        $url = $url ?? $request->query('url');

        $productsByCategory = Category::where('url', $url)
            ->where('status', 1) // Only active categories
            ->with(['products' => function ($query) {
                $query->where('status', 1); // Only active products
            }])
            ->get();

            // return $productsByCategory;dd();

        return Inertia::render('User/Product/ProductByCategoryPage', [
            'productsByCategory' => $productsByCategory
        ]);
    }

}
