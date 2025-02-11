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

        return Inertia::render('Admin/Category/CategoryPage', [
            'categories' => $categories,
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

    //======================show add/edit category page=====================//
    public function showSaveCategory(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $id = $request->query('id');
        $category = Category::with('section:id,sec_name', 'parent:id,name')->where('id', $id)->first();
        $parent_category = Category::where('parent_id', null)->get();
        $sections = Section::get();
        return Inertia::render('Admin/Category/CategorySavePage', [
            'parent_category' => $parent_category,
            'sections' => $sections,
            'category' => $category,
        ]);
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
            'image.max' => 'Image size should not exceed 2MB.',
            'discount.numeric' => 'Discount must be a numeric value.',
            'discount.min' => 'Discount cannot be negative.',
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
                'image' => 'nullable|max:2048',
                'discount' => 'nullable|numeric|min:0',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable|max:500',
                'meta_keywords' => 'nullable|max:255',
                'status' => 'required|boolean',
            ],
            $messages,
        );

        //Generate URL from category name
        $validatedData['url'] = strtolower(str_replace(' ', '_', trim($request->name)));

        // Ensure URL is unique in categories table
        $counter = 1;
        $originalUrl = $validatedData['url'];
        while (Category::where('url', $validatedData['url'])->exists()) {
            $validatedData['url'] = $originalUrl . '_' . $counter;
            $counter++;
        }

        // Handle image upload properly
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('category', $imageNameToStore, 'public');

            // Assign the image URL to validated data
            $validatedData['image'] = $image_url;
        }

        //Create category
        $category = Category::create($validatedData);

        if ($category) {
            return redirect()
                ->route('show.category')
                ->with([
                    'message' => 'Category created successfully',
                    'status' => true,
                    'code' => 200,
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'message' => 'Failed to create category',
                    'status' => false,
                    'code' => 500,
                ]);
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
            'image.max' => 'Image size should not exceed 2MB.',
            'discount.numeric' => 'Discount must be a numeric value.',
            'discount.min' => 'Discount cannot be negative.',
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
                'image' => 'nullable|max:2048',
                'discount' => 'nullable|numeric|min:0',
                'meta_title' => 'nullable|max:255',
                'meta_description' => 'nullable|max:500',
                'meta_keywords' => 'nullable|max:255',
                'status' => 'required|boolean',
            ],
            $messages,
        );

        // Check if name is changed, then update URL, otherwise keep old URL
        if ($category->name !== $request->name) {
            $validatedData['url'] = strtolower(str_replace(' ', '_', trim($request->name)));

            // Ensure URL is unique in categories table
            $counter = 1;
            $originalUrl = $validatedData['url'];
            while (Category::where('url', $validatedData['url'])->where('id', '!=', $id)->exists()) {
                $validatedData['url'] = $originalUrl . '_' . $counter;
                $counter++;
            }
        } else {
            $validatedData['url'] = $category->url; // Keep the old URL
        }

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

        if ($category) {
            $data = ['message' => 'Category updated successfully', 'status' => true, 'code' => 200];
            return to_route('show.category')->with($data);
        } else {
            $data = ['message' => 'Failed to update category', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //========================delete category========================//
    public function deleteCategory($id)
    {
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

        if ($category) {
            $data = ['message' => 'Category deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to delete category', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
