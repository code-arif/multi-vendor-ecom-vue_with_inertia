<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorBankDetails;
use App\Models\VendorBusinessDetails;
use Illuminate\Support\Facades\Auth;

class VendorProfileManageController extends Controller
{
    //========================get vendor details===========================//
    public function showVendorProfile()
    {
        $vendor_profile = Vendor::where('id', Auth::guard('admin')->user()->vendor_id)->first() ?? [];
        $vendor_business = VendorBusinessDetails::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->first() ?? [];
        $vendor_bank = VendorBankDetails::where('vendor_id', Auth::guard('admin')->user()->vendor_id)->first() ?? [];
        return Inertia::render('Admin/Vendor/VendorProfileBankBusinessPage', ['vendor_profile' => $vendor_profile, 'vendor_business' => $vendor_business, 'vendor_bank' => $vendor_bank]);
    }

    //=========================update vendor profile==========================//
    public function updateVendorProfile(Request $request)
    {
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        // dd($request->all());

        $validatedData = $request->validate(
            [
                'name' => 'required|max:255',
                'phone' => 'required|numeric|digits_between:11,15',
                'address' => 'required|max:255',
                'city' => 'required|max:100',
                'country' => 'required|max:100',
                'zip' => 'nullable|string|max:20',
            ],
            [
                'name.required' => 'Vendor name is required.',
                'name.max' => 'Vendor name must not be greater than 255 characters.',
                'phone.required' => 'Please enter your mobile',
                'phone.numeric' => 'Mobile must be a number',
                'phone.digits_between' => 'Mobile must be between 11 to 15 digits',
                'country.required' => 'Country is required',
                'city.required' => 'City is required',
                'address.required' => 'Address is required',
                'address.max' => 'Address must not be greater than 255 characters.',
            ],
        );
        $vendor = Vendor::findOrFail($vendor_id);
        if ($vendor->update($validatedData)) {
            $data = ['message' => 'Vendor profile updated successfully', 'status' => true, 'code' => 200];
            return to_route('show.admin.dashboard')->with($data);
        } else {
            $data = ['message' => 'Failed to update vendor profile', 'status' => false, 'code' => 500];
            return redirect()->back()->with($data);
        }
    }

    //========================update vendor business details=====================//
    public function updateVendorBusiness(Request $request)
    {
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        $validatedBusiness = $request->validate(
            [
                'shop_name' => 'required|max:255',
                'shop_address' => 'required|max:255',
                'shop_zip' => 'required|string|max:20',
                'shop_city' => 'nullable|max:100',
                'shop_mobile' => 'nullable|numeric|digits_between:11,15',
                'shop_email' => 'nullable|email|unique:vendor_business_details,shop_email,' . $vendor_id . ',vendor_id',
                'shop_website' => 'nullable|string|max:255',
                'shop_pin' => 'required|string|max:10',
                // 'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'shop_license' => 'required|max:255',
                'shop_pan' => 'required|max:50|unique:vendor_business_details,shop_pan,' . $vendor_id . ',vendor_id',
                'shop_gst' => 'required|max:50|unique:vendor_business_details,shop_gst,' . $vendor_id . ',vendor_id',
                'shop_description' => 'nullable|max:255',
            ],
            [
                'shop_name.required' => 'Shop name is required.',
                'shop_address.required' => 'Shop address is required.',
                'shop_zip.required' => 'Shop zip code is required.',
                'shop_zip.max' => 'Zip code should not exceed 20 characters.',
                'shop_city.max' => 'City name should not exceed 100 characters.',
                'shop_mobile.numeric' => 'Mobile number should be numeric.',
                'shop_mobile.digits_between' => 'Mobile number should be between 11 to 15',
                'shop_email.email' => 'Enter a valid email address.',
                'shop_email.unique' => 'This email is already associated with another shop.',
                'shop_website.string' => 'Enter a valid URL for the shop website.',
                'shop_website.max' => 'Website URL should not exceed 255 characters.',
                'shop_pin.required' => 'Shop PIN is required.',
                'shop_pin.max' => 'Shop PIN should not exceed 10 characters.',
                // 'shop_image.image' => 'Uploaded file must be an image.',
                // 'shop_image.mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                // 'shop_image.max' => 'Image size must not exceed 2MB.',
                'shop_license.required' => 'Shop license is required.',
                'shop_license.max' => 'Shop license should not exceed 255 characters.',
                'shop_pan.required' => 'PAN number is required.',
                'shop_pan.unique' => 'This PAN is already associated with another shop.',
                'shop_gst.required' => 'GST number is required.',
                'shop_gst.unique' => 'This GST number is already associated with another shop.',
                'shop_description.max' => 'Description should not exceed 255 characters.',
            ],
        );

        $validatedBusiness['vendor_id'] = $vendor_id;

        $vendorBusiness = VendorBusinessDetails::where('vendor_id', $vendor_id)->first();

        if ($vendorBusiness) {
            $vendorBusiness->update($validatedBusiness);
        } else {
            VendorBusinessDetails::create($validatedBusiness);
        }

        return redirect()
            ->route('show.admin.dashboard')
            ->with([
                'message' => 'Vendor business updated successfully',
                'status' => true,
                'code' => 200,
            ]);
    }

    //=======================update vendor bank details=====================//
    public function updateVendorBank(Request $request)
    {
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        $validateBank = $request->validate(
            [
                'bank_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'account_holder_name' => 'required|string|max:255',
                'branch_name' => 'required|string|max:255',
                'ifsc_code' => 'required|string|max:20|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
                'swift_code' => 'nullable|string|max:20',
                'bank_address' => 'nullable|string|max:255',
            ],
            [
                'bank_name.required' => 'The bank name is required.',
                'bank_name.max' => 'The bank name may not be greater than 255 characters.',

                'account_number.required' => 'The account number is required.',
                'account_number.max' => 'The account number may not be greater than 50 characters.',

                'account_holder_name.required' => 'The account holder name is required.',
                'account_holder_name.max' => 'The account holder name may not be greater than 255 characters.',

                'branch_name.required' => 'The branch name is required.',
                'branch_name.max' => 'The branch name may not be greater than 255 characters.',

                'ifsc_code.required' => 'The IFSC code is required.',
                'ifsc_code.regex' => 'The IFSC code format is invalid. Example: ABCD0123456.',
                'ifsc_code.max' => 'The IFSC code may not be greater than 20 characters.',

                'swift_code.max' => 'The SWIFT code may not be greater than 20 characters.',

                'bank_address.max' => 'The bank address may not be greater than 500 characters.',
            ],
        );

        // Ensure the vendor_id is included for identification
        $validateBank['vendor_id'] = $vendor_id;

        // Check if a bank record exists for this vendor
        $vendorBank = VendorBankDetails::where('vendor_id', $vendor_id)->first();

        if ($vendorBank) {
            $vendorBank->update($validateBank);
        } else {
            VendorBankDetails::create($validateBank);
        }

        return redirect()
            ->route('show.admin.dashboard')
            ->with([
                'message' => 'Vendor bank details updated successfully.',
                'status' => true,
                'code' => 200,
            ]);
    }

    //=====================shwo vendor desclaimer =====================//
    public function showVendorDesclaimer()
    {
        return Inertia::render('Admin/Vendor/DesclaimerPage');
    }
}
