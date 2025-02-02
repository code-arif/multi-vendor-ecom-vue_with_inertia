<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    //==========================get all section in section list page ==========================//
    public function showSections()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $sections = Section::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/Section/SectionPage', [
            'sections' => $sections,
        ]);
    }

    //=========================update section status ==========================//
    public function changeSectionStatus(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $section = Section::find($id);
        if (!$section) {
            $data = ['message' => 'Section not found', 'status' => $section->status, 'code' => 200];
            return redirect()->back()->with($data);
        }

        $section->status = $request->status;
        $section->save();

        $data = ['message' => 'Status changed successfully', 'status' => $section->status, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //===================add section =====================//
    public function addSection(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validation
        $request->validate(
            [
                'sec_name' => 'required|string|max:50',
                'status' => 'required|boolean',
            ],
            [
                'sec_name.required' => 'Section name is required',
                'sec_name.max' => 'Section name should not be more than 50 characters',
                'status.required' => 'Status is required',
                'status.boolean' => 'Status should be true or false',
            ],
        );

        // Create section
        $createSection = Section::create([
            'sec_name' => $request->input('sec_name'),
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN), // Convert to boolean
        ]);

        if ($createSection) {
            $data = ['message' => 'Section added successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to add section', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }

    //============================update section =====================//
    public function updateSection(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validate request
        $request->validate(
            [
                'sec_name' => 'required|string|max:50',
                'status' => 'required|boolean',
            ],
            [
                'sec_name.required' => 'Section name is required',
                'sec_name.max' => 'Section name should not be more than 50 characters',
                'status.required' => 'Status is required',
                'status.boolean' => 'Status should be true or false',
            ],
        );

        // Find section
        $section = Section::findOrFail($id);

        // Update section
        $updateSection = $section->update([
            'sec_name' => $request->input('sec_name'),
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN),
        ]);

        if ($updateSection) {
            $data = ['message' => 'Section updated successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to update section', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }

    //===========================delete section=====================//
    public function deleteSection($id){
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $section = Section::findOrFail($id)->delete();

        if($section){
            $data = ['message' => 'Section deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        }else{
            $data = ['message' => 'Failed to delete section', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
