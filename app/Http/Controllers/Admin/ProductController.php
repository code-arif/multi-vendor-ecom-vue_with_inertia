<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * =====================================
     *  Admin Product Controller
     * =====================================
     *  Handles admin-related product operations.
     */

    //======================show product list in proudct list page =====================//
    public function showProduct()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $products = Product::with(['vendor:id,name', 'section:id,sec_name', 'category:id,name', 'brand:id,name', 'admin:id,type,name'])
            ->orderByDesc('created_at')
            ->get();

        // return $products;
        return Inertia::render('Admin/Product/ProductPage', ['products' => $products]);
    }

    //=======================proudct status change=======================//
    public function changeProductStatus(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        //find product
        $product = Product::find($id);
        if (!$product) {
            $data = ['message' => 'Product not found', 'status' => false, 'code' => 404];
            return redirect()->back()->with($data);
        }
        //change status
        $product->status = $request->status;
        $product->save();

        $data = ['message' => 'Product status changed successfully', 'status' => true, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //======================show add/edit product page=====================//
    public function showSaveProduct(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $id = $request->query('id');
        $categories = Section::with('categories')->get();
        $brands = Brand::get();
        $products = Product::where('id', $id)
            ->with(['vendor:id,name', 'section:id,sec_name', 'category:id,name', 'brand:id,name'])
            ->first();
        // return $brands;dd();

        return Inertia::render('Admin/Product/ProductSavePage', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    //======================save product=====================//
    public function addProduct(Request $request)
    {
        // Check if the logged-in user is superadmin, admin, subadmin or vendor
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin', 'subadmin', 'vendor'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $message = [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Category is not found',

            'brand_id.exists' => 'Invalid brand selection.',

            'product_name.required' => 'Product name must be provided.',
            'product_name.string' => 'Product name must be a valid string.',
            'product_name.min' => 'Product name must be at least 3 characters.',
            'product_name.max' => 'Product name must not exceed 255 characters.',

            'sku.required' => 'SKU must be provided.',
            'sku.unique' => 'This SKU already exists.',

            'price.required' => 'Price must be provided.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',

            'image.max' => 'Each image size must not exceed 3MB.',

            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',

            'stock_status.required' => 'Stock status must be selected.',
            'stock_status.in' => 'Invalid stock status.',

            'remark.in' => 'Invalid remark selection.',

            'short_description.string' => 'Short description must be a valid text.',

            'meta_title.max' => 'Meta title cannot exceed 255 characters.',
            'meta_description.max' => 'Meta description cannot exceed 255 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters.',

            'has_discount.required' => 'Discount status must be selected.',
            'has_discount.boolean' => 'Discount status must be a boolean value.',

            'discount_price.numeric' => 'Discount price must be a valid number.',
            'discount_price.min' => 'Discount price cannot be negative.',

            'is_featured.boolean' => 'Invalid featured status.',

            'status.required' => 'Status selection is required.',
            'status.boolean' => 'Invalid status selection.',
        ];

        $request->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'product_name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string',
                'sku' => 'required|string|unique:products,sku',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|max:3048',
                'stock_quantity' => 'required|integer|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
                'remark' => 'nullable|in:popular,new,top,special,trending,regular',
                'short_description' => 'nullable|string',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:250',
                'meta_keywords' => 'nullable|string|max:250',
                'has_discount' => 'required|boolean',
                'discount_price' => 'nullable|numeric|min:0',
                'is_featured' => 'nullable|boolean',
                'status' => 'required|boolean',
            ],
            $message,
        );

        $productData = $request->except('image');

        // Determine the correct vendor_id and admin_id
        if ($admin->type === 'vendor') {
            $productData['vendor_id'] = $admin->id; // Vendor is creating the product
            $productData['admin_id'] = null; // Initially, admin_id is null, will be updated upon approval
            $productData['status'] = 0; // Set status to 0 for pending approval
        } else {
            // superadmin, admin, or subadmin
            $productData['vendor_id'] = $request->input('vendor_id'); // Use vendor_id from the request (if provided). Important for Subadmins!
            $productData['admin_id'] = $admin->id; // Admin/Subadmin is creating/adding the product.
        }

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('product', $imageNameToStore, 'public');
            $productData['image'] = $image_url;
        }

        $productData['slug'] = Str::slug($request->input('product_name'));
        // dd($productData);

        $product = Product::create($productData);

        if ($product) {
            $data = ['message' => 'Product created successfully.', 'status' => true, 'code' => 200];
            return to_route('show.product')->with($data);
        } else {
            $data = ['message' => 'Failed to create product.', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //=======================update product==========================//
    public function updateProduct(Request $request, $id)
    {
        // Check if the logged-in user is superadmin, admin, subadmin, or vendor
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin', 'subadmin', 'vendor'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $product = Product::findOrFail($id);

        $message = [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Category is not found',
            'brand_id.exists' => 'Invalid brand selection.',
            'product_name.required' => 'Product name must be provided.',
            'product_name.string' => 'Product name must be a valid string.',
            'product_name.min' => 'Product name must be at least 3 characters.',
            'product_name.max' => 'Product name must not exceed 255 characters.',
            'sku.required' => 'SKU must be provided.',
            'sku.unique' => 'This SKU already exists.',
            'price.required' => 'Price must be provided.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'image.max' => 'Each image size must not exceed 3MB.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'stock_status.required' => 'Stock status must be selected.',
            'stock_status.in' => 'Invalid stock status.',
            'remark.in' => 'Invalid remark selection.',
            'short_description.string' => 'Short description must be a valid text.',
            'meta_title.max' => 'Meta title cannot exceed 255 characters.',
            'meta_description.max' => 'Meta description cannot exceed 250 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 250 characters.',
            'has_discount.required' => 'Discount status must be selected.',
            'has_discount.boolean' => 'Discount status must be a boolean value.',
            'discount_price.numeric' => 'Discount price must be a valid number.',
            'discount_price.min' => 'Discount price cannot be negative.',
            'is_featured.boolean' => 'Invalid featured status.',
            'status.required' => 'Status is required.',
            'status.boolean' => 'Invalid status.',
        ];

        $validatedData = $request->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'product_name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string',
                'sku' => "required|string|unique:products,sku,{$id}",
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|max:3048',
                'stock_quantity' => 'required|integer|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
                'remark' => 'nullable|in:popular,new,top,special,trending,regular',
                'short_description' => 'nullable|string',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:250',
                'meta_keywords' => 'nullable|string|max:250',
                'has_discount' => 'required|boolean',
                'discount_price' => 'nullable|numeric|min:0',
                'is_featured' => 'nullable|boolean',
                'status' => 'required|boolean',
            ],
            $message,
        );

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image (if exists)
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('product', $imageNameToStore, 'public');

            // Assign new image URL to product data
            $validatedData['image'] = $image_url;
        }

        // Generate slug from product name
        $validatedData['slug'] = Str::slug($request->input('product_name'));

        // Update product
        $product->update($validatedData);

        return redirect()
            ->route('show.product')
            ->with([
                'message' => 'Product updated successfully.',
                'status' => true,
                'code' => 200,
            ]);
    }

    //===================product delete==================//
    public function deleteProduct($id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        if ($product) {
            $data = ['message' => 'Product deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to delete product', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
