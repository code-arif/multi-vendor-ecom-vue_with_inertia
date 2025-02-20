<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return Inertia::render('User/Product/ProductsPage', [
            'products' => $products,
            'all_products' => $all_products,
        ]);
    }

    //======================show product details page ======================//
    public function showProductDetailsPage(Request $request, $id = null)
    {
        // Ensure we get the correct product ID
        $id = $id ?? $request->query('id');

        if (!$id) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }

        // Fetch product details along with relationships
        $productDetails = Product::with(['product_details', 'specifications', 'productImages', 'category:id,name', 'brand:id,name', 'vendor.vendor_business'])->find($id);

        // return $productDetails;dd();

        // Check if the product exists
        if (!$productDetails) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return Inertia::render('User/Product/ProductDetailsPage', [
            'productDetails' => $productDetails,
        ]);
    }

    //======================get product by category =====================//
    public function productByCategory(Request $request, $url = null)
    {
        $url = $url ?? $request->query('url');

        $productsByCategory = Category::where('url', $url)
            ->where('status', 1)
            ->with([
                'products' => function ($query) {
                    $query->where('status', 1);
                },
            ])
            ->get();

        return Inertia::render('User/Product/ProductByCategoryPage', [
            'productsByCategory' => $productsByCategory,
        ]);
    }

    //=====================get product by brand=======================//
    public function productByBrand(Request $request, $id = null)
    {
        $id = $id ?? $request->query('id');
        $productsByBrand = Brand::where('id', $id)
            ->where('status', 1)
            ->with([
                'products' => function ($query) {
                    $query->where('status', 1);
                },
            ])
            ->get();

        return Inertia::render('User/Product/ProductByBrandPage', [
            'productsByBrand' => $productsByBrand,
        ]);
    }
}
