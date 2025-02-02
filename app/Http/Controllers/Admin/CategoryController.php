<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    //=========================show all categories=========================//
    public function showCategory()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        $categories = Category::with('section:id,sec_name', 'parent:id,name')->orderBy('id', 'desc')->get();
        $sections = Section::get();

        // return [$categories, $sections];

        return Inertia::render('Admin/Category/CategoryPage', [
            'categories' => $categories,
            'sections' => $sections,
        ]);
    }

    //=========================update section status ==========================//
    public function changeCategoryStatus(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $category = Category::find($id);
        if (!$category) {
            $data = ['message' => 'Section not found', 'status' => $category->status, 'code' => 200];
            return redirect()->back()->with($data);
        }

        $category->status = $request->status;
        $category->save();

        $data = ['message' => 'Status changed successfully', 'status' => $category->status, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //=========================add new category=========================//
    public function addCategory(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validation
        $messages = [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name cannot exceed 255 characters.',
            'section_id.required' => 'Section selection is required.',
            'section_id.exists' => 'Selected section does not exist.',
            'parent_id.exists' => 'Selected parent category does not exist.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'image.image' => 'The image must be a valid image file.',
            'image.mimes' => 'The image format must be jpeg, png, jpg, or webp.',
            'image.max' => 'Image size should not exceed 2MB.',
            'discount.numeric' => 'Discount must be a numeric value.',
            'discount.min' => 'Discount cannot be negative.',
            'url.url' => 'The URL must be a valid format.',
            'meta_title.max' => 'Meta title cannot exceed 255 characters.',
            'meta_description.max' => 'Meta description cannot exceed 500 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.boolean' => 'Status must be either 0 or 1.',
        ];

        $validatedData = $request->validate(
            [
                'name' => 'required|max:255',
                'section_id' => 'required|exists:sections,id',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'discount' => 'nullable|numeric|min:0',
                'url' => 'nullable|url',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable|max:500',
                'meta_keywords' => 'nullable|max:255',
                'status' => 'required|boolean',
            ],
            $messages,
        );

        // Handle image upload properly
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('category', $imageNameToStore, 'public');

            // Assign the image URL to validated data
            $validatedData['image'] = $image_url;
        }

        // Create category
        $category = Category::create($validatedData);

        if ($category) {
            $data = ['message' => 'Category created successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to create category', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //=========================add new category=========================//
    public function updateCategory(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $category = Category::findOrFail($id);

        // Validation rules with custom messages
        $messages = [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name cannot exceed 255 characters.',
            'section_id.required' => 'Section selection is required.',
            'section_id.exists' => 'Selected section does not exist.',
            'parent_id.exists' => 'Selected parent category does not exist.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'image.image' => 'The image must be a valid image file.',
            'image.mimes' => 'The image format must be jpeg, png, jpg, or webp.',
            'image.max' => 'Image size should not exceed 2MB.',
            'discount.numeric' => 'Discount must be a numeric value.',
            'discount.min' => 'Discount cannot be negative.',
            'url.url' => 'The URL must be a valid format.',
            'meta_title.max' => 'Meta title cannot exceed 255 characters.',
            'meta_description.max' => 'Meta description cannot exceed 500 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.boolean' => 'Status must be either 0 or 1.',
        ];

        $validatedData = $request->validate(
            [
                'name' => 'required|max:255',
                'section_id' => 'required|exists:sections,id',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'discount' => 'nullable|numeric|min:0',
                'url' => 'nullable|url',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable|max:500',
                'meta_keywords' => 'nullable|max:255',
                'status' => 'required|boolean',
            ],
            $messages
        );

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image (if exists)
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('category', $imageNameToStore, 'public');

            // Assign new image URL to category
            $validatedData['image'] = $image_url;
        }

        // Update category
        $category->update($validatedData);

        return redirect()->back()->with([
            'message' => 'Category updated successfully',
            'status' => true,
            'code' => 200,
        ]);
    }

    public function deleteCategory($id){
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $category = Category::findOrFail($id);
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();

        if($category){
            $data = ['message' => 'Category deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        }else{
            $data = ['message' => 'Failed to delete category', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
