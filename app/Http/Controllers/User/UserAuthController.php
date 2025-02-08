<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserAuthController extends Controller
{
    public function showLogin(){
        return Inertia::render('User/Auth/UserLoginPage');
    }
}
