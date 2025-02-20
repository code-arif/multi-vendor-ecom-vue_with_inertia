<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    // ========================show user login page===================//
    public function showLogin()
    {
        return Inertia::render('User/Auth/UserLoginPage');
    }

    //========================send otp to user email to otp verification ==========================//
    public function userLogin(Request $request)
    {
        try {
            $email = $request->input('email');
            $otp = rand(10000, 99999);

            session(['email' => $email]);

            Mail::to($email)->send(new OTPMail($otp));

            User::updateOrCreate(['email' => $email], ['otp' => $otp]);

            return to_route('show.otp')->with(['message' => 'OTP sent to your email. Please check your inbox to verify your OTP', 'status' => true]);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(['message' => $e->getMessage(), 'status' => false]);
        }
    }

    //=====================show otp verification page========================//
    public function showOTP()
    {
        return Inertia::render('User/Auth/otpVerifyPage');
    }

    //===========================verify otp==================================//
    public function verifyOTP(Request $request)
    {
        $request->validate(
            [
                'otp' => 'required|numeric|digits:5',
            ],
            [
                'otp.required' => 'Please enter OTP',
                'otp.numeric' => 'OTP should be numeric',
                'otp.digits' => 'OTP should be 5 digits',
            ],
        );

        $email = session('email');
        $otp = $request->input('otp');

        $userFind = User::where('email', $email)->where('otp', $otp)->first();

        if ($userFind) {
            User::where('email', $email)->update(['otp' => '0']);

            // Generate JWT token for the user
            $token = JWTToken::generateToken($email, $userFind->id);

            session()->forget('email');

            return to_route('show.home.page')
                ->with(['message' => 'OTP verified successfully', 'status' => true])
                ->cookie('token', $token, 60 * 24 * 30);
        } else {
           return redirect()->back()->with(['message' => 'Invalid OTP', 'status' => false]);
        }
    }

    //=========================user logout========================//
    public function userLogout(){
        return redirect()->route('show.user.login')
        ->cookie('token', '', -1);
    }
}
