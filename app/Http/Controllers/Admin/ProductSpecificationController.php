<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use App\Models\ProductSpecification;
use App\Models\ProductVideos;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductSpecificationController extends Controller
{
    //===================show save product details==================//
    public function showProductSpecification(Request $request, $id = null)
    {
        // Check if logged-in user is admin or superadmin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $id = $request->query('id');
        $product_specifications = null;
        // Get Existing Specifications (if any)
        $product = Product::findOrFail($id);
        $product_specifications = ProductSpecification::where('product_id', $id)->first();
        // return $product_specifications;dd();
        return Inertia::render('Admin/Product/SaveProductSpecificationPage', [
            'product' => $product,
            'product_specifications' => $product_specifications,
        ]);
    }

    //=======================save product specification=====================//
    public function saveProductSpecification(Request $request)
    {
        // Check if logged-in user is admin or superadmin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validate the input data
        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'material' => 'nullable|string',
                'weight' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'volume' => 'nullable|numeric',
                'additional_price' => 'nullable|numeric',
            ],
            [
                'product_id.required' => 'Product is required.',
                'product_id.exists' => 'Product does not exist.',
                'color.string' => 'Color must be a string.',
                'size.string' => 'Size must be a string.',
                'material.string' => 'Material must be a string.',
                'weight.numeric' => 'Weight must be a number.',
                'length.numeric' => 'Length must be a number.',
                'width.numeric' => 'Width must be a number.',
                'height.numeric' => 'Height must be a number.',
                'volume.numeric' => 'Volume must be a number.',
                'additional_price.numeric' => 'Additional price must be a number.',
            ],
        );

        // Prepare data for insertion into the product_specifications table
        $specificationData = [
            'product_id' => $request->product_id,
            'color' => $request->color,
            'size' => $request->size,
            'material' => $request->material,
            'weight' => $request->weight ?? null,
            'length' => $request->length ?? null,
            'width' => $request->width ?? null,
            'height' => $request->height ?? null,
            'volume' => $request->volume ?? null,
            'additional_price' => $request->additional_price ?? null,
            'weight_unit' => 'kg',
            'length_unit' => 'cm',
            'width_unit' => 'cm',
            'height_unit' => 'cm',
            'volume_unit' => 'cm3',
        ];

        // Save the new specification data to the product_specifications table
        ProductSpecification::create($specificationData);

        // Return success message and redirect back to the product list
        $data = ['message' => 'Product specification saved successfully', 'status' => true];
        return redirect()->route('show.product')->with($data);
    }

    //==========================update product specification==========================//
    public function updateProductSpecification(Request $request)
    {
        // Check if logged-in user is admin or superadmin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validate the input data
        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'material' => 'nullable|string',
                'weight' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'volume' => 'nullable|numeric',
                'additional_price' => 'nullable|numeric',
            ],
            [
                'product_id.required' => 'Product is required.',
                'product_id.exists' => 'Product does not exist.',
                'color.string' => 'Color must be a string.',
                'size.string' => 'Size must be a string.',
                'material.string' => 'Material must be a string.',
                'weight.numeric' => 'Weight must be a number.',
                'length.numeric' => 'Length must be a number.',
                'width.numeric' => 'Width must be a number.',
                'height.numeric' => 'Height must be a number.',
                'volume.numeric' => 'Volume must be a number.',
                'additional_price.numeric' => 'Additional price must be a number.',
            ],
        );

        // Find the existing product specification
        $productSpecification = ProductSpecification::where('product_id', $request->product_id)->first();

        // If the specification does not exist, return an error
        if (!$productSpecification) {
            return response()->json(['error' => 'Product specification not found'], 404);
        }

        // Prepare data for updating the product_specifications table
        $specificationData = [
            'color' => $request->color ?? $productSpecification->color,
            'size' => $request->size ?? $productSpecification->size,
            'material' => $request->material ?? $productSpecification->material,
            'weight' => $request->weight ?? $productSpecification->weight,
            'length' => $request->length ?? $productSpecification->length,
            'width' => $request->width ?? $productSpecification->width,
            'height' => $request->height ?? $productSpecification->height,
            'volume' => $request->volume ?? $productSpecification->volume,
            'additional_price' => $request->additional_price ?? $productSpecification->additional_price,
            'weight_unit' => $productSpecification->weight_unit, // Keep existing unit values
            'length_unit' => $productSpecification->length_unit,
            'width_unit' => $productSpecification->width_unit,
            'height_unit' => $productSpecification->height_unit,
            'volume_unit' => $productSpecification->volume_unit,
        ];

        // Update the existing product specification
        $productSpecification->update($specificationData);

        // Return success message and redirect back to the product list
        $data = ['message' => 'Product specification updated successfully', 'status' => true];
        return redirect()->route('show.product')->with($data);
    }

    //==============================save product images=========================
    public function saveProductImage(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'image_path' => 'required|array', // Ensure it's an array
                'image_path.*' => 'image|max:2048', // Apply image validation to each file
            ],
            [
                'product_id.required' => 'Product is required',
                'product_id.exists' => 'Product does not exist',
                'image_path.required' => 'At least one image is required',
                'image_path.array' => 'Invalid image format',
                'image_path.*.image' => 'Each file must be an image',
                'image_path.*.max' => 'Each image size must be less than 2MB',
            ],
        );

        // Get existing product images
        $existingImages = ProductImages::where('product_id', $request->product_id)->get();

        // Delete old images from storage and database
        foreach ($existingImages as $image) {
            if (file_exists(public_path('storage/' . $image->image_path))) {
                unlink(public_path('storage/' . $image->image_path));
            }
            $image->delete();
        }

        // Upload new images
        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image) {
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image_url = $image->storeAs('product_images', $imageName, 'public');

                ProductImages::create([
                    'product_id' => $request->product_id,
                    'image_path' => $image_url,
                ]);
            }
        }

        return redirect()
            ->route('show.product')
            ->with([
                'message' => 'Product images updated successfully',
                'status' => true,
            ]);
    }

    //=====================save product video=====================//
    // public function saveProductVideo(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:10240',
    //         'video_url' => 'nullable|url',
    //     ], [
    //         'product_id.required' => 'Product is required',
    //         'product_id.exists' => 'Product does not exist',
    //         'video.file' => 'Video must be a file',
    //         'video.mimes' => 'Video must be in mp4, mov, avi, webm format',
    //         'video.max' => 'Video size must be less than 10MB',
    //         'video_url.url' => 'Video URL must be a valid URL',
    //     ]);

    //     $product = Product::findOrFail($request->product_id);

    //     // Delete old videos
    //     ProductVideos::where('product_id', $product->id)->delete();

    //     $videoPath = null;

    //     // Check if a video file is uploaded
    //     if ($request->hasFile('video')) {
    //         $videoPath = $request->file('video')->store('product_videos', 'public');
    //     } elseif ($request->filled('video_url')) {
    //         // If a video URL is provided
    //         $videoPath = $request->video_url;
    //     }

    //     // Save new video record
    //     if ($videoPath) {
    //         ProductVideos::create([
    //             'product_id' => $product->id,
    //             'video_url' => $videoPath,
    //         ]);
    //     }

    //     return back()->with('status', 'Product video updated successfully');
    // }
}
