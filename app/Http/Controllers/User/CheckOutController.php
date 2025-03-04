<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CheckoutSession;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    //========================show checkout page========================//
    public function showCheckoutPage(Request $request)
    {
        $user_id = $request->header('id');
        $checkoutSession = CheckoutSession::where('user_id', $user_id)->first();

        if (!$checkoutSession) {
            return response()->json(['message' => 'No checkout session found'], 404);
        }
        $shipping_addresses = ShippingAddress::where('user_id', $user_id)->get();
        return Inertia::render('User/Checkout/CheckoutPage', [
            'shipping_addresses' => $shipping_addresses,
            'selected_cart_items' => json_decode($checkoutSession->selected_cart_items, true),
            'total' => $checkoutSession->total,
        ]);
    }

    //======================store cart data in checkout session table====================//
    public function storeCartData(Request $request) {
        $user_id = $request->header('id');
    
        $validated_data = $request->validate([
            'selectedProducts' => 'required|array',
            'total' => 'required|numeric',
        ]);
    
       CheckoutSession::updateOrCreate(
            ['user_id' => $user_id],
            [
                'selected_cart_items' => json_encode($validated_data['selectedProducts']),
                'total' => $validated_data['total'],
                'expires_at' => now()->addMinutes(30),
            ]
        );
    
        return redirect()->route('show.checkout.page');
    }
}
