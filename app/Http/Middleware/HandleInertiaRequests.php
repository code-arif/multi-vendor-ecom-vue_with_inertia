<?php

namespace App\Http\Middleware;

use App\Models\User;
use Firebase\JWT\JWT;
use Inertia\Middleware;
use App\Helper\JWTToken;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }


    public function share(Request $request)
    {
        // Retrieve token from cookie or header
        $token = $request->cookie('token') ?? $request->bearerToken();

        $user = null;

        if ($token) {
            // Verify JWT token and get payload
            $payload = JWTToken::verifyToken($token);

            // Check if payload is valid and find the user
            if (is_object($payload) && isset($payload->id)) {
                $user = User::find($payload->id);
                $cartItemCount = ProductCart::where('user_id', $user->id)->count();
            }
        }

        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'status' => fn() => $request->session()->get('status'),
                'code' => fn() => $request->session()->get('code'),
            ],

            // forn admin and vendor default Auth
            'auth' => [
                'user' => Auth::guard('admin')->check()
                    ? [
                        'id' => Auth::guard('admin')->id(),
                        'name' => Auth::guard('admin')->user()->name,
                        // 'email' => Auth::guard('admin')->user()->email,
                        'image' => Auth::guard('admin')->user()->image,
                        'type' => Auth::guard('admin')->user()->type ?? 'Admin',
                    ]
                    : null,
            ],

            //for user token validation
            'authUser' => [
                'authenticatedUser' => $user ?? null,
                'count_cart_item' => $cartItemCount ?? 0, 
            ],
        ]);
    }
}
