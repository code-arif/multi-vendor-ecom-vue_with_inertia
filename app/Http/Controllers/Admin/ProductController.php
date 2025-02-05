<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //======================show product list in proudct list page =====================//
    public function showProduct()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $products = Product::with(['vendor:id,name', 'section:id,sec_name', 'category:id,name', 'brand:id,name', 'admin:id,type'])->get();
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
        // return $brands;dd();

        return Inertia::render('Admin/Product/ProductSavePage', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    //================add product=====================//
    public function addProduct(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin', 'subadmin', 'vendor'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $rule =  [
            'vendor_id.required' => 'Vendor is not selected',
            'admin_id.required' => 'Admin is not selected',

            'category_id.required' => 'Category is selected',
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

        // Validate request data
        $request->validate(
            [
                'vendor_id' => 'nullable',
                'admin_id' => 'nullable',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'product_name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string',
                'sku' => 'required|string|unique:products,sku',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable||max:3048',
                'stock_quantity' => 'required|integer|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
                'remark' => 'nullable|in:popular,new,top,special,trending,regular',
                'short_description' => 'nullable|string',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'has_discount' => 'required|boolean',
                'discount_price' => 'nullable|numeric|min:0',
                'is_featured' => 'nullable|boolean',
                'status' => 'required|boolean',
            ], $rule
        );

        //get admin_id or vendor_id
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        $admin_id = Auth::guard('admin')->user()->id;


        // Handle Image Upload
        $image_url = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('product', $imageNameToStore, 'public');

            // Assign the image URL to validated data
            // $validatedData['image'] = $image_url;
        }

        // Insert Product
        $product = Product::create([
            'vendor_id' => $vendor_id,
            'admin_id' => $admin_id,
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'product_name' => $request->input('product_name'),
            'slug' => Str::slug($request->input('product_name')),
            'sku' => $request->input('sku'),
            'price' => $request->input('price'),
            'image' => $image_url,
            'stock_quantity' => $request->input('stock_quantity'),
            'stock_status' => $request->input('stock_status'),
            'remark' => $request->input('remark'),
            'short_description' => $request->input('short_description'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'has_discount' => $request->input('discount_price') ? true : false,
            'discount_price' => $request->input('discount_price'),
            'is_featured' => $request->input('is_featured') ?? false,
            'status' => $request->input('status'),
        ]);

        // $product = Product::create($request->all());

        // $product = Product::create($validatedData);

        if ($product) {
            $data = ['message' => 'Product created successfully.', 'status' => true, 'code' => 200];
            return to_route('show.product')->with($data);
        }
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
