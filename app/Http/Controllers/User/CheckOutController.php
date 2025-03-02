<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    //========================show checkout page========================//
    public function showCheckoutPage(Request $request)
    {
        $user_id = $request->header('id');
        $shipping_addresses = ShippingAddress::where('user_id', $user_id)->get();
        return Inertia::render('User/Checkout/CheckoutPage', [
            'shipping_addresses' => $shipping_addresses,
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
            ]
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
