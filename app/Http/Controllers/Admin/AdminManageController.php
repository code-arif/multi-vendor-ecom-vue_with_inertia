<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminManageController extends Controller
{
    //======================get all admins/subadmins/vendors/======================//
    public function AdminManage($type = null)
    {
        // Check if the logged-in user is superadmin
        if (Auth::guard('admin')->user()->type !== 'superadmin' && Auth::guard('admin')->user()->type !== 'admin') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Valid admin types
        $validTypes = ['admin', 'subadmin', 'vendor'];

        if (!$type) {
            $admins = Admin::all();
        } elseif (in_array($type, $validTypes)) {
            $admins = Admin::where('type', $type)->get();
        } else {
            return response()->json(['error' => 'Invalid admin type'], 400);
        }

        return Inertia::render('Admin/AdminManage/AdminManagePage', [
            'admins' => $admins,
        ]);
    }

    //======================get vendor details by admin, superadmin ======================//
    public function getVendorDetails($id)
    {
        // Check if the logged-in user is superadmin or admin
        if (!in_array(Auth::guard('admin')->user()->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $vendor_details = Admin::where('id', $id)->with('vendor_personal', 'vendor_bank', 'vendor_business')->first();
        // return $vendor_details;

        // Ensure relations are not null, set default empty array
        $vendor_details->vendor_personal = $vendor_details->vendor_personal ?? [];
        $vendor_details->vendor_bank = $vendor_details->vendor_bank ?? [];
        $vendor_details->vendor_business = $vendor_details->vendor_business ?? [];

        return Inertia::render('Admin/AdminManage/ViewVendorDetailsPage', [
            'vendor_details' => $vendor_details,
        ]);
    }

    //=====================change status of admin/subadmin/vendor=====================//
    public function changeStatus($id, Request $request)
    {
        // Check if the logged-in user is superadmin or admin
        if (!in_array(Auth::guard('admin')->user()->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $admin->status = $request->status; // Get status from the request
        $admin->save();

        $data = ['message' => 'Status changed successfully', 'status' => $admin->status, 'code' => 200];
        return redirect()->back()->with($data);
    }
}
