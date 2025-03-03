<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use App\Models\ShippingAddress;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    //==================show user profile page=================//
    public function showProfile(Request $request)
    {
        $user_id = $request->header('id');

        if (!$user_id) {
            return response()->json(['error', 'user not found'], 401);
        }

        $user_email = User::where('id', $user_id)->first();

        $user = CustomerProfile::where('user_id', $user_id)->first();
        // dd($user);

        return Inertia::render('User/Profile/ProfilePage', [
            'user' => $user,
            'user_email' => $user_email
        ]);
    }

    //==================save user profile=================//
    public function saveProfile(Request $request)
    {
        $user_id = $request->header('id');

        // Validate Request Data
        $validatedData = $request->validate(
            [
                'cus_name' => 'required|string|max:100',
                'cus_address' => 'required|string|max:500',
                'cus_country' => 'required|string|max:50',
                'cus_city' => 'required|string|max:50',
                'cus_state' => 'nullable|string|max:50',
                'cus_zip' => 'nullable|string|max:20',
                'cus_phone' => 'required|string|max:20',
                'cus_fax' => 'nullable|string|max:50',
            ],
            [
                'cus_name.required' => 'Please enter your name',
                'cus_address.required' => 'Please enter your address',
                'cus_country.required' => 'Please enter your country',
                'cus_city.required' => 'Please enter your city',
                'cus_phone.required' => 'Please enter your phone number',
            ],
        );

        // Add user_id to validated data
        $validatedData['user_id'] = $user_id;

        // Update or Create Profile
        CustomerProfile::updateOrCreate(['user_id' => $user_id], $validatedData);

        return redirect()
            ->back()
            ->with(['message' => 'Profile updated successfully', 'status' => true]);
    }

    //==================show shipping address page================//
    public function showShippingAddress(Request $request)
    {
        $user_id = $request->header('id');
        $user_email = User::where('id', $user_id)->first();
        $shipping_addresses = ShippingAddress::where('user_id', $user_id)->get();
        return Inertia::render('User/Profile/ShippingAddressPage', [
            'shipping_addresses' => $shipping_addresses,
            'user_email' => $user_email
        ]);
    }

    //=====================create shipping address=====================//
    public function createShipAddress(Request $request)
    {
        $user_id = $request->header('id');

        $validated_data = $request->validate(
            [
                'ship_name' => 'required|string|max:100',
                'ship_add' => 'required|string|max:255',
                'ship_city' => 'nullable|string|max:100',
                'ship_state' => 'nullable|string|max:100',
                'zip' => 'required|string|max:100',
                'ship_country' => 'nullable|string|max:100',
                'ship_phone' => 'required|string|max:50',
            ],
            [
                'ship_name.required' => 'Please enter your name',
                'ship_add.required' => 'Please enter your address',
                'ship_phone.required' => 'Please enter your phone number',
                'zip.required' => 'Please enter your zip code',
            ],
        );

        $validated_data['user_id'] = $user_id;

        ShippingAddress::create($validated_data);

        return redirect()
            ->back()
            ->with(['message' => 'Shipping address created successfully', 'status' => true]);
    }

    //====================update shipping address=====================//
    public function updateShipAddress(Request $request, $id)
    {
        $user_id = $request->header('id');

        $validated_data = $request->validate(
            [
                'ship_name' => 'required|string|max:100',
                'ship_add' => 'required|string|max:255',
                'ship_city' => 'nullable|string|max:100',
                'ship_state' => 'nullable|string|max:100',
                'zip' => 'required|string|max:100',
                'ship_country' => 'nullable|string|max:100',
                'ship_phone' => 'required|string|max:50',
            ],
            [
                'ship_name.required' => 'Please enter your name',
                'ship_add.required' => 'Please enter your address',
                'ship_phone.required' => 'Please enter your phone number',
                'zip.required' => 'Please enter your zip code',
            ],
        );

        $shippingAddress = ShippingAddress::where('id', $id)->where('user_id', $user_id)->firstOrFail();

        $shippingAddress->update($validated_data);

        return redirect()
            ->back()
            ->with(['message' => 'Shipping address updated successfully', 'status' => true]);
    }

    //=====================delete shipping address=====================//
    public function deleteShipAddress(Request $request, $id)
    {
        $user_id = $request->header('id');
        ShippingAddress::where('user_id', $user_id)->where('id', $id)->delete();
        return redirect()
            ->back()
            ->with(['message' => 'Shipping address deleted successfully', 'status' => true]);
    }
}
