<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    //==========================get all brand in brand list page ==========================//
    public function showBrand()
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $brand = Brand::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/Brand/BrandPage', [
            'brand' => $brand,
        ]);
    }

    //=========================update brand status ==========================//
    public function changeBrandStatus(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $brand = Brand::find($id);
        if (!$brand) {
            $data = ['message' => 'Brand not found', 'status' => $brand->status, 'code' => 200];
            return redirect()->back()->with($data);
        }

        $brand->status = $request->status;
        $brand->save();

        $data = ['message' => 'Status changed successfully', 'status' => $brand->status, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //===================add brand =====================//
    public function addBrand(Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        //validation message
        $message = [
            'name.required' => 'Brand name is required',
            'name.max' => 'Brand name should not be more than 100 characters',
            'status.required' => 'Status is required',
            'status.boolean' => 'Status should be true or false',
            'logo.max' => 'Logo size should not be more than 2MB',
            'description.max' => 'Description should not be more than 5000 characters',
        ];
        $validateData = $request->validate(
            [
                'name' => 'required|string|max:100',
                'status' => 'required|boolean',
                'logo' => 'nullable|max:2048',
                'description' => 'nullable|max:5000',
            ],
            $message,
        );
        // Handle logo upload properly
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoNameToStore = uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo_url = $logo->storeAs('brand', $logoNameToStore, 'public');

            // Assign the image URL to validated data
            $validateData['logo'] = $logo_url;
        }

        // Create brand
        $brand = Brand::create($validateData);

        if ($brand) {
            $data = ['message' => 'Brand created successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to create brand', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //============================update brand =====================//
    public function updateBrand(Request $request, $id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        // Find brand
        $brand = Brand::findOrFail($id);

        //validation message
        $message = [
            'name.required' => 'Brand name is required',
            'name.max' => 'Brand name should not be more than 100 characters',
            'status.required' => 'Status is required',
            'status.boolean' => 'Status should be true or false',
            'logo.max' => 'Logo size should not be more than 2MB',
            'description.max' => 'Description should not be more than 5000 characters',
        ];
        $validateData = $request->validate(
            [
                'name' => 'required|string|max:50',
                'status' => 'required|boolean',
                'logo' => 'nullable|max:2048',
                'description' => 'nullable|max:5000',
            ],
            $message,
        );
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo (if exists)
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $logo = $request->file('logo');
            $logoNameToStore = uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo_url = $logo->storeAs('brand', $logoNameToStore, 'public');

            // Assign new logo URL to brand
            $validateData['logo'] = $logo_url;
        }

        // Update brand
        $brand->update($validateData);

        if ($brand) {
            $data = ['message' => 'Brand updated successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to update Band', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //=========================detete brand==========================//
    public function deleteBrand($id)
    {
        // Check if the logged-in user is superadmin or admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || !in_array($admin->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $brand = Brand::findOrFail($id);
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }
        $brand->delete();

        if ($brand) {
            $data = ['message' => 'Brand deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to delete brand !', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }
}
