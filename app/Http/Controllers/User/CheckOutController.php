<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    public function showCheckoutPage(){
        return Inertia::render('User/Checkout/CheckoutPage');
    }
}
