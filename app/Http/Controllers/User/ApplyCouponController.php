<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Models\CouponCategory;
use App\Http\Controllers\Controller;

class ApplyCouponController extends Controller
{
    // public function applyCoupon(Request $request)
    // {
    //     // 1. Fetch user from database
    //     $user = User::find($request->header('id'));
    //     if (!$user) {
    //         return response()->json(['error' => 'User not found!'], 404);
    //     }
    
    //     $couponCode = $request->coupon_code;
    //     $categoryId = $request->category_id; // User je category er product kinche
    //     $cartTotal = ProductCart::where('user_id', $user->id)->sum('subtotal');
    
    //     // 2. Coupon Code Validity Check
    //     $coupon = Coupon::where('coupon_code', $couponCode)->first();
    //     if (!$coupon) {
    //         return response()->json(['error' => 'Invalid coupon code!'], 404);
    //     }
    
    //     // 3. Coupon Status Check (Active or Not)
    //     if (!$coupon->coupon_status) {
    //         return response()->json(['error' => 'This coupon is inactive!'], 403);
    //     }
    
    //     // 4. Expiry Date Check
    //     $currentDate = Carbon::now()->toDateString();
    //     if ($coupon->coupon_end_date < $currentDate) {
    //         return response()->json(['error' => 'This coupon has expired!'], 403);
    //     }
    
    //     // 5. Minimum Order Amount Check
    //     if ($coupon->minimum_order_amount && $cartTotal < $coupon->minimum_order_amount) {
    //         return response()->json(['error' => 'Minimum order amount required: ' . $coupon->minimum_order_amount], 403);
    //     }
    
    //     // 6. Category-wise Coupon Restriction
    //     $validCategories = CouponCategory::where('coupon_id', $coupon->id)->pluck('category_id')->toArray();
    //     if (!in_array($categoryId, $validCategories)) {
    //         return response()->json(['error' => 'This coupon is not valid for this category!'], 403);
    //     }
    
    //     // 7. Check if user already used the coupon
    //     $alreadyUsed = CouponUser::where('user_id', $user->id)
    //         ->where('coupon_id', $coupon->id)
    //         ->exists();
    
    //     if ($alreadyUsed) {
    //         return response()->json(['error' => 'You have already used this coupon!'], 403);
    //     }
    
    //     // 8. Usage Limit Check
    //     if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
    //         return response()->json(['error' => 'Coupon usage limit is finished!'], 403);
    //     }
    
    //     // 9. Calculate Discount
    //     $discountAmount = 0;
    //     if ($coupon->coupon_type === 'fixed') {
    //         $discountAmount = $coupon->coupon_discount;
    //     } elseif ($coupon->coupon_type === 'percentage') {
    //         $discountAmount = ($cartTotal * $coupon->coupon_discount) / 100;
    //     }
    
    //     $newTotal = $cartTotal - $discountAmount;
    
    //     // 10. Update Cart Price After Coupon Applied
    //     ProductCart::where('user_id', $user->id)->update(['subtotal' => $newTotal]);
    
    //     // 11. Update Coupon Usage Count
    //     $coupon->increment('used_count');
    
    //     // Save coupon usage for the user
    //     CouponUser::create([
    //         'user_id' => $user->id,
    //         'coupon_id' => $coupon->id,
    //     ]);
    
    //     return response()->json([
    //         'success' => 'Coupon applied successfully!',
    //         'discount' => $discountAmount,
    //         'new_total' => $newTotal,
    //     ]);
    // }

    public function applyCoupon(Request $request)
    {
        // 1. Fetch user from database
        $user = User::find($request->header('id'));
        if (!$user) {
            return response()->json(['error' => 'User not found!'], 404);
        }

        $couponCode = $request->coupon_code;
        $categoryIds = $request->category_ids; // Receive as array
        $cartTotal = ProductCart::where('user_id', $user->id)->sum('subtotal');

        // 2. Coupon Code Validity Check
        $coupon = Coupon::where('coupon_code', $couponCode)->first();
        if (!$coupon) {
            return response()->json(['error' => 'Invalid coupon code!'], 404);
        }

        // 3. Coupon Status Check (Active or Not)
        if (!$coupon->coupon_status) {
            return response()->json(['error' => 'This coupon is inactive!'], 403);
        }

        // 4. Expiry Date Check
        $currentDate = Carbon::now()->toDateString();
        if ($coupon->coupon_end_date < $currentDate) {
            return response()->json(['error' => 'This coupon has expired!'], 403);
        }

        // 5. Minimum Order Amount Check
        if ($coupon->minimum_order_amount && $cartTotal < $coupon->minimum_order_amount) {
            return response()->json(['error' => 'Minimum order amount required: ' . $coupon->minimum_order_amount], 403);
        }

        // 6. Category-wise Coupon Restriction
        $validCategories = CouponCategory::where('coupon_id', $coupon->id)->pluck('category_id')->toArray();

        // Check if all selected products have valid category
        foreach ($categoryIds as $categoryId) {
            if (!in_array($categoryId, $validCategories)) {
                return response()->json(['error' => 'This coupon is not valid for one or more categories!'], 403);
            }
        }

        // 7. Check if user already used the coupon
        $alreadyUsed = CouponUser::where('user_id', $user->id)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json(['error' => 'You have already used this coupon!'], 403);
        }

        // 8. Usage Limit Check
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['error' => 'Coupon usage limit is finished!'], 403);
        }

        // 9. Calculate Discount
        $discountAmount = 0;
        if ($coupon->coupon_type === 'fixed') {
            $discountAmount = $coupon->coupon_discount;
        } elseif ($coupon->coupon_type === 'percentage') {
            $discountAmount = ($cartTotal * $coupon->coupon_discount) / 100;
        }

        $newTotal = $cartTotal - $discountAmount;

        // 10. Update Cart Price After Coupon Applied
        ProductCart::where('user_id', $user->id)->update(['subtotal' => $newTotal]);

        // 11. Update Coupon Usage Count
        $coupon->increment('used_count');

        // Save coupon usage for the user
        CouponUser::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
        ]);

        return response()->json([
            'success' => 'Coupon applied successfully!',
            'discount' => $discountAmount,
            'new_total' => $newTotal,
        ]);
    }
    
}
