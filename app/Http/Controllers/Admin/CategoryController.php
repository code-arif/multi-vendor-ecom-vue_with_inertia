<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
