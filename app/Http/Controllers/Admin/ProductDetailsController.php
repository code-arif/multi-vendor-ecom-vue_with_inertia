<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductDetailsController extends Controller
{
    //===================show save product details==================//
    public function showSaveProductDetails(Request $request, $id = null)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $id = $request->query('id');
        $productDetails = null;

        if ($id) {
            $product = Product::findOrFail($id);
            $productDetails = ProductDetails::where('product_id', $id)->first();
        }

        return Inertia::render('Admin/Product/SaveProductDetailsPage', [
            'product' => $product,
            'productDetails' => $productDetails,
        ]);
    }

    // ===================save product details==================//
    public function saveProductDetails(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'long_description' => 'nullable|string',
            ],
            [
                'product_id.required' => 'Product id required',
                'product_id.exists' => 'Product does not exist',
                'long_description.string' => 'Long description must be a string',
            ],
        );

        ProductDetails::create([
            'product_id' => $request->input('product_id'),
            'long_description' => $request->input('long_description'),
        ]);

        $data = ['message' => 'Product details updated successfully', 'status' => true];

        return redirect()->route('show.product')->with($data);
    }

    //=====================update product details=====================//
    public function updateProductDetails(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'long_description' => 'nullable|string',
            ],
            [
                'product_id.required' => 'Product id required',
                'product_id.exists' => 'Product does not exist',
                'long_description.string' => 'Long description must be a string',
            ],
        );

        $product_details = ProductDetails::where('product_id', $request->product_id)->first();
          // If the specification does not exist, return an error
        if (!$product_details) {
            $data = ['message' => 'Product details not found', 'status' => 404];
            return redirect()->back()->with($data);
        }

        $update_product_details = [
            'long_description' => $request->input('long_description') ?? $product_details->long_description,
        ];

        $product_details->update($update_product_details);
        $data = ['message' => 'Product details updated successfully', 'status' => true];
        return redirect()->route('show.product')->with($data);
    }

    //==========================show product details==========================//
    public function showProductDetails(Request $request, $id = null)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Ensure we get the correct product ID
        $id = $id ?? $request->query('id');

        if (!$id) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }

        // Fetch product details along with relationships
        $productDetails = Product::with(['vendor:id,name', 'admin:id,type,name', 'product_details', 'specifications', 'productImages', 'category:id,name', 'brand:id,name'])->find($id);
        // return $productDetails;dd();

        // Check if the product exists
        if (!$productDetails) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return Inertia::render('Admin/Product/ProductDetailsPage', [
            'productDetails' => $productDetails,
        ]);
    }
}
