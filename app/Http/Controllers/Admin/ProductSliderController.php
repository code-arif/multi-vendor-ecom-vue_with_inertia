<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductSlider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductSliderController extends Controller
{
    //===================show product slider in datatable=====================//
    public function showProductSlider()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        $products = Product::select('id', 'product_name')->get();
        // return $products;dd();
        $slider_list = ProductSlider::with('product:id,product_name')->get();
        return Inertia::render('Admin/Product/ProductSliderPage', [
            'products' => $products,
            'slider_list' => $slider_list,
        ]);
    }

    //=========================update slider status ==========================//
    public function changeProductSliderStatus(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $slider = ProductSlider::find($id);
        if (!$slider) {
            $data = ['message' => 'Slider not found', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        }

        $slider->status = $request->status;
        $slider->save();

        $data = ['message' => 'Status changed successfully', 'status' => true, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //=======================add new slider=============================//
    public function saveProductSlider(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        //validation message
        $message = [
            'product_id.required' => 'Product is required',
            'product_id.exists' => 'Product does not exist',
            'image.required' => 'Slider image is required',
            'image.mimes' => 'Slider image must be a file of type: jpeg, jpg, png, gif, svg,',
            'image.max' => 'Slider image size should not exceed 2MB',
            'title.required' => 'Slider title is required',
            'title.max' => 'Slider title should not exceed 100 characters',
            'short_description.required' => 'Slider description is required',
            'short_description.max' => 'Slider description should not exceed 255 characters',
            'status.required' => 'Slider status is required',
            'stauts.booleand' => 'Slider status must be booleand',
            'price.required' => 'Slider price is required',
            'price.numeric' => 'Slider price must be a number',
        ];
        $validateData = $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'title' => 'required|string|max:255',
                'short_description' => 'required|string|max:255',
                'price' => 'required|numeric',
                'status' => 'required|boolean',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ],
            $message,
        );

        // Check if a slider already exists for this product before creating
        if (ProductSlider::where('product_id', $request->product_id)->exists()) {
            $data = ['message' => 'A slider already exists for this product', 'status' => false, 'code' => 422];
            return redirect()->back()->with($data);
        }

        // Handle image upload properly
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $slider_url = $image->storeAs('product_slider', $imageNameToStore, 'public');

            // Assign the image URL to validated data
            $validateData['image'] = $slider_url;
        }

        // Create brand
        $slider = ProductSlider::create($validateData);

        if ($slider) {
            $data = ['message' => 'Slider created successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to create slider', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //============================update slidedr =====================//
    public function updateProductSlider(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Find slider
        $slider = ProductSlider::findOrFail($id);

        // Validation messages
        $message = [
            'product_id.required' => 'Product is required',
            'product_id.exists' => 'Product does not exist',
            'title.required' => 'Slider title is required',
            'title.max' => 'Slider title should not exceed 100 characters',
            'short_description.required' => 'Slider description is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'status.required' => 'Status is required',
            'image.mimes' => 'Slider image must be a file of type: jpeg, jpg, png, gif, svg',
            'image.max' => 'Slider image size should not exceed 2MB',
        ];
        $validateData = $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'title' => 'required|max:100',
                'short_description' => 'required',
                'price' => 'required|numeric',
                'status' => 'required|in:0,1',
                'image' => 'sometimes|nullable|mimes:jpeg,jpg,png,gif,svg|max:2048', // Image is optional
            ],
            $message,
        );

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image (if exists)
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('product_slider', $imageNameToStore, 'public');

            // Assign new image URL to slider
            $validateData['image'] = $image_url;
        }

        // **Fixed Condition**: Ignore current slider ID while checking existence
        if (
            ProductSlider::where('product_id', $request->product_id)
                ->where('id', '!=', $id) // Ignore current slider
                ->exists()
        ) {
            $data = ['message' => 'A slider already exists for this product', 'status' => false, 'code' => 422];
            return redirect()->back()->with($data);
        }

        // Update slider
        $slider->update($validateData);

        if ($slider) {
            $data = ['message' => 'Slider updated successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to update slider', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //==========================delete slider==========================//
    public function deleteProductSlider($id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $slider = ProductSlider::findOrFail($id);
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        if ($slider) {
            $data = ['message' => 'Slider deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to delete slider !', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
