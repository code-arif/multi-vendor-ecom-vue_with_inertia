<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Mail\VendorStatusMail;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
    // public function changeStatus($id, Request $request)
    // {
    //     // Check if the logged-in user is superadmin or admin
    //     if (!in_array(Auth::guard('admin')->user()->type, ['superadmin', 'admin'])) {
    //         return response()->json(['error' => 'Unauthorized access'], 403);
    //     }

    //     $admin = Admin::find($id);
    //     if (!$admin) {
    //         return response()->json(['error' => 'Admin not found'], 404);
    //     }

    //     $admin->status = $request->status;
    //     $admin->save();

    //     $data = ['message' => 'Status changed successfully', 'status' => $admin->status, 'code' => 200];
    //     return redirect()->back()->with($data);
    // }

    public function changeStatus($id, Request $request)
    {
        if (!in_array(Auth::guard('admin')->user()->type, ['superadmin', 'admin'])) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $vendor = Vendor::where('id', $admin->vendor_id)->first();

        $admin->status = $request->status;
        $vendor->status = $request->status;
        $admin->save();
        $vendor->save();

        // Email Pathano
        Mail::to($admin->email)->send(new VendorStatusMail($admin, $request->status));

        return redirect()->back()->with('message', 'Status changed and email sent successfully');
    }
}
