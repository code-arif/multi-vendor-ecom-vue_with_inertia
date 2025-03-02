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
}
