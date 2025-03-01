<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    //=================get user list====================//
    public function getUserList(){
        $users = User::with('customer')->get();
        return Inertia::render('Admin/UserList/UserListPage',[
            'users' => $users,
        ]);
    }
}
