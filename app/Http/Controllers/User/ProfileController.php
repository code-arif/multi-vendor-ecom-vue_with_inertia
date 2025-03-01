<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\Colors\Rgb\Channels\Red;

class ProfileController extends Controller
{
    //==================show user profile page=================//
    public function showProfile(Request $request){
        $user_id = $request->header('id');

        if (!$user_id) {
            return response()->json(['error', 'user not found'], 401);
        }

        $user = CustomerProfile::where('user_id', $user_id)->with('user')->first();

        return Inertia::render('User/Profile/ProfilePage', [
            'user' => $user,
        ]);
    }

    //==================save user profile=================//
    public function saveProfile(Request $request)
    {
        $user_id = $request->header('id');
    
        // Validate Request Data
        $validatedData = $request->validate([
            'cus_name' => 'required|string|max:100',
            'cus_address' => 'required|string|max:500',
            'cus_country' => 'required|string|max:50',
            'cus_city' => 'required|string|max:50',
            'cus_state' => 'nullable|string|max:50',
            'cus_zip' => 'nullable|string|max:20',
            'cus_phone' => 'required|string|max:20',
            'cus_fax' => 'nullable|string|max:50',
        ], [
            'cus_name.required' => 'Please enter your name',
            'cus_address.required' => 'Please enter your address',
            'cus_country.required' => 'Please enter your country',
            'cus_city.required' => 'Please enter your city',
            'cus_phone.required' => 'Please enter your phone number',
        ]);
    
        // Add user_id to validated data
        $validatedData['user_id'] = $user_id;
    
        // Update or Create Profile
        CustomerProfile::updateOrCreate(
            ['user_id' => $user_id],
            $validatedData 
        );
    
        return redirect()->back()->with(['message' => 'Profile updated successfully', 'status' => true]);
    }
}
