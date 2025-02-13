<?php

namespace App\Http\Controllers\Vendor;

use Exception;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\VendorConfirmationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VendorController extends Controller
{
    //========================show vendor login page=======================//
    public function showVendorAccountCreatePage()
    {
        return Inertia::render('Vendor/Auth/VendorAccountPage');
    }

    //===================create vendor account=====================//
    public function createVendorAccount(Request $request)
    {
        DB::beginTransaction();

        $rule = [
            'name.required' => 'Please enter your name',
            'name.max' => 'Name should not be more than 50 characters',
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'Email already exists',
            'phone.required' => 'Please enter your phone number',
            'phone.numeric' => 'Please enter a valid phone number',
            'address.required' => 'Please enter your address',
            'address.max' => 'Address should not be more than 250 characters',
            'city.required' => 'Please enter your city',
            'city.max' => 'City should not be more than 50 characters',
            'state.max' => 'State should not be more than 50 characters',
            'country.required' => 'Please enter your country',
            'country.max' => 'Country should not be more than 50 characters',
            'zip.numeric' => 'Please enter a valid zip code',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password should be at least 4 characters',
            'confirm_password.required' => 'Please confirm your password',
            'confirm_password.same' => 'Passwords do not match',
        ];

        try {
            $request->validate(
                [
                    'name' => 'required|max:50',
                    'email' => 'required|email|unique:vendors',
                    'phone' => 'required|numeric',
                    'address' => 'required|max:250',
                    'city' => 'required|max:50',
                    'state' => 'nullable|max:50',
                    'country' => 'required|max:50',
                    'zip' => 'nullable|numeric',
                    'password' => 'required|min:4',
                    'confirm_password' => 'required|same:password',
                ],
                $rule,
            );

            // Insert data into vendor table
            $vendor = Vendor::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'zip' => $request->zip,
                'pin' => rand(1000, 9999),
                'status' => 0,
            ]);

            // Insert corresponding admin record
            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'type' => 'vendor',
                'vendor_id' => $vendor->id,
                'mobile' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 0,
            ]);

            // Send email using Laravel Mailables
            Mail::to($vendor->email)->send(new VendorConfirmationMail($vendor));

            DB::commit();

            $data = ['message' => 'Your account has been created successfully. Please verify your email address to activate your account', 'status' => true];
            return redirect()->route('show.admin.login')->with($data);
        } catch (Exception $e) {
            DB::rollBack();

            $data = ['message' => $e->getMessage()];
            // $data = ['message' => ' An error occurred while creating your account', 'status' => false];
            return redirect()->back()->with($data);
        }
    }

    //=====================vendor confimation =====================//
    public function vendorConfirmation($email) {
        $email = base64_decode($email);

        $vendor = Vendor::where('email', $email)->first();
        $admin = Admin::where('email', $email)->first();

        if (!$vendor || !$admin) {
            return redirect()->route('show.admin.login')->with('message', 'Invalid confirmation link.');
        }

        // Jodi already confirmed hoy
        if ($vendor->confirm == 'Yes' && $admin->confirm == 'Yes') {
            return redirect()->route('show.admin.login')->with('message', 'Your account is already confirmed.');
        }

        // Status update for Vendor and Admin
        $vendor->update(['confirm' => 'Yes']);
        $admin->update(['confirm' => 'Yes']);

        // Farewell Email Send
        $messageData = [
            'email' => $email,
            'name' => $vendor->name
        ];

        Mail::send('emails.vendorConfirmed', $messageData, function($message) use ($email) {
            $message->to($email)->subject('Welcome! Your Account is Confirmed');
        });

        return redirect()->route('show.admin.login')->with('message', 'Your account has been successfully confirmed! You can now log in.');
    }

}
