<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminAuthController extends Controller
{
    //========================show admin dashboard page ========================//
    public function ShowAdminLogin()
    {
        return Inertia::render('Admin/Auth/AdminLoginPage');
    }

    //========================show admin login page ========================//
    public function AdminLogin(Request $request)
    {
        // Validate the request data
        $request->validate(
            [
                'email' => 'required|email|max:255',
                'password' => 'required|min:4',
            ],
            [
                'email.required' => 'The email is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'The password is required.',
                'password.min' => 'Password must be at least 4 characters.',
            ],
        );

        // Attempt login using the 'admin' guard
        if (
            Auth::guard('admin')->attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ])
        ) {
            $data = ['message' => 'Login Successfull', 'status' => true, 'code' => 200];
            $request->session()->regenerate();
            return to_route('show.admin.dashboard')->with($data);
        } else {
            $data = ['message' => 'Email or Password is incorrect', 'status' => false, 'code' => 401];
            return redirect()->back()->with($data);
        }
    }

    //========================admin logout=======================//
    public function AdminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('show.admin.login');
    }

    //=========================admin profile update =========================//
    //show admin profile page
    public function showAdminProfile()
    {
        $admin = Auth::guard('admin')->user();
        return Inertia::render('Admin/Auth/AdminProfilePage', ['admin' => $admin]);
    }

    //update admin profile
    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin === null) {
            $data = ['message' => 'You are not logged in', 'status' => false, 'code' => 401];
            return redirect()->back()->with($data);
        }

        $update_admin = $request->validate(
            [
                'name' => 'required|max:255',
                'mobile' => 'required|numeric|digits_between:11,15',
                'status' => 'required|boolean',
                'zip' => 'nullable|string|max:10',
                'address' => 'nullable|max:255',
            ],
            [
                'name.required' => 'Please enter your name',
                'name.max' => 'Name must be less than 255 characters',
                'mobile.required' => 'Please enter your mobile',
                'mobile.numeric' => 'Mobile must be a number',
                'mobile.digits_between' => 'Mobile must be between 11 to 15 digits',
                'status.required' => 'Please enter your status',
                'status.boolean' => 'Status must be a boolean',
                'zip.max' => 'Zip must be less than 10 characters',
                'address.max' => 'Address must be less than 255 characters',
            ],
        );

        if ($request->hasFile('image')) {
            if ($admin->image) {
                Storage::disk('public')->delete($admin->image);
            }

            $image = $request->file('image');
            $imageNameToStore = uniqid() . '.' . $image->getClientOriginalExtension();
            $image_url = $image->storeAs('admin', $imageNameToStore, 'public');
            $admin->image = $image_url;
        }

        if ($admin->update($update_admin)) {
            $data = ['message' => 'Admin profile updated successfully', 'status' => true, 'code' => 200];
            return to_route('show.admin.dashboard')->with($data);
        } else {
            $data = ['message' => 'Failed to update admin profile', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //admin password update
    public function updateAdminPassword(Request $request)
    {
        // Validate the request
        $request->validate(
            [
                'current_password' => 'required',
                'password' => 'required|min:4|confirmed',
            ],
            [
                'current_password.required' => 'The current password is required.',
                'password.required' => 'The new password is required.',
                'password.min' => 'The new password must be at least 4 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
            ],
        );

        // Get the authenticated admin
        $admin = Auth::guard('admin')->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            $data = ['message' => 'Current password is incorrect', 'status' => false, 'code' => 401];
            return redirect()->back()->with($data);
        } else {
            // Update the admin password
            $admin->update([
                'password' => Hash::make($request->password),
            ]);

            $data = ['message' => 'Password updated successfully', 'status' => true, 'code' => 200];
            return to_route('show.admin.dashboard')->with($data);
        }
    }
}
