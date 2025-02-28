<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Coupon;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CouponCategory;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    //====================show coupon list=====================//
    public function showCoupon()
    {
        $admin = Auth::guard('admin')->user();

        $coupons = Coupon::query()->with('vendor:id,name')->with('categories:id,name');
        // return $coupons->get();dd();
        
        $categories = Section::with('categories')->get();
        $vendor = Admin::where('type', 'vendor')->select('vendor_id', 'name')->get();

        if ($admin->type == 'vendor') {
            $coupons->where('vendor_id', $admin->vendor_id);
        }

        return Inertia::render('Admin/Coupon/CouponListPage', [
            'coupons' => $coupons->orderBy('id', 'desc')->get(),
            'categories' => $categories,
            'vendor' => $vendor,
        ]);
    }

    //==================change coupon status=====================//
    public function changeCouponStatus(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            $data = ['message' => 'Coupon not found', 'status' => false, 'code' => 200];
            return redirect()->back()->with($data);
        }

        $coupon->coupon_status = $request->coupon_status;
        $coupon->save();

        $data = ['message' => 'Status changed successfully', 'status' => true, 'code' => 200];
        return redirect()->back()->with($data);
    }

    //=========================delete coupon===========================//
    public function deleteCoupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();

        if ($coupon) {
            $data = ['message' => 'Coupon deleted successfully', 'status' => true, 'code' => 200];
            return redirect()->back()->with($data);
        } else {
            $data = ['message' => 'Failed to delete coupon !', 'status' => false, 'code' => 400];
            return redirect()->back()->with($data);
        }
    }

    //======================add coupon=======================//
    public function addCoupon(Request $request)
    {
        $admin = Auth::guard('admin')->user();
    
        $messages = [
            'category_ids.array' => 'Categories must be an array.',
            'category_ids.*.integer' => 'Each category ID must be an integer.',
            'category_ids.*.exists' => 'One or more category IDs do not exist.',
            'coupon_code.required' => 'Coupon code is required.',
            'coupon_code.unique' => 'This coupon code already exists.',
            'coupon_type.required' => 'Coupon type is required.',
            'coupon_type.in' => 'Coupon type must be fixed or percentage.',
            'coupon_discount.required' => 'Discount amount is required.',
            'coupon_discount.numeric' => 'Discount must be a number.',
            'coupon_start_date.required' => 'Start date is required.',
            'coupon_end_date.required' => 'End date is required.',
            'coupon_end_date.after' => 'End date must be after the start date.',
            'minimum_order_amount.numeric' => 'Minimum order amount must be a number.',
            'usage_limit.integer' => 'Usage limit must be an integer.',
        ];
    
        $validated = $request->validate(
            [
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'integer|exists:categories,id',
                'coupon_code' => 'required|unique:coupons,coupon_code',
                'coupon_type' => ['required', Rule::in(['fixed', 'percentage'])],
                'coupon_discount' => 'required|numeric|min:0',
                'coupon_start_date' => 'required|date',
                'coupon_end_date' => 'required|date|after:coupon_start_date',
                'coupon_status' => 'boolean',
                'minimum_order_amount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
            ],
            $messages
        );
    
        if ($admin->type === 'superadmin') {
            $vendor_id = $validated['vendor_id'] = $request->input('vendor_id');
            if (!$vendor_id) {
                return redirect()->back()->with(['message' => 'Vendor id is required.', 'status' => false, 'code' => 500]);
            }
        } else {
            $validated['vendor_id'] = $admin->vendor_id;
        }
    
        DB::beginTransaction();
    
        try {
            // Create Coupon (excluding 'category_ids' from the validated data)
            $couponData = collect($validated)->except('category_ids')->toArray();
            $coupon = Coupon::create($couponData);
    
            // Attach Categories to Pivot Table
            if ($coupon && $request->has('category_ids')) {
                foreach ($request->category_ids as $category_id) {
                    CouponCategory::create([
                        'coupon_id' => $coupon->id,
                        'category_id' => $category_id,
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->back()->with(['message' => 'Coupon created successfully', 'status' => true, 'code' => 200]);
    
        } catch (Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->with([
                'message' => 'Failed to create coupon: ' . $e->getMessage(),
                'status' => false,
                'code' => 500
            ]);
        }
    }
    


    //=========================update coupon==============================//
    public function updateCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
    
        // Custom validation messages
        $messages = [
            'category_ids.array' => 'Categories must be an array.',
            'category_ids.*.integer' => 'Each category ID must be an integer.',
            'category_ids.*.exists' => 'One or more category IDs do not exist.',
            'coupon_code.required' => 'Coupon code is required.',
            'coupon_code.unique' => 'This coupon code already exists.',
            'coupon_type.required' => 'Coupon type is required.',
            'coupon_type.in' => 'Coupon type must be fixed or percentage.',
            'coupon_discount.required' => 'Discount amount is required.',
            'coupon_discount.numeric' => 'Discount must be a number.',
            'coupon_start_date.required' => 'Start date is required.',
            'coupon_end_date.required' => 'End date is required.',
            'coupon_end_date.after' => 'End date must be after the start date.',
            'minimum_order_amount.numeric' => 'Minimum order amount must be a number.',
            'usage_limit.integer' => 'Usage limit must be an integer.',
        ];
    
        // Validation rules
        $validated = $request->validate([
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'coupon_code' => "required|unique:coupons,coupon_code,{$id}",
            'coupon_type' => ['required', Rule::in(['fixed', 'percentage'])],
            'coupon_discount' => 'required|numeric|min:0',
            'coupon_start_date' => 'required|date',
            'coupon_end_date' => 'required|date|after:coupon_start_date',
            'coupon_status' => 'boolean',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
        ], $messages);
    
        $admin = Auth::guard('admin')->user();
        if ($admin->type === 'superadmin') {
            $vendor_id = $validated['vendor_id'] = $request->input('vendor_id');
            if (!$vendor_id) {
                return redirect()->back()->with(['message' => 'Vendor id is required.', 'status' => false, 'code' => 500]);
            }
        } else {
            $validated['vendor_id'] = $admin->vendor_id;
        }
    
        DB::beginTransaction();
    
        try {
            // Update Coupon (excluding 'category_ids' from the validated data)
            $couponData = collect($validated)->except('category_ids')->toArray();
            $coupon->update($couponData);
    
            if ($request->has('category_ids')) {
                // Delete previous categories
                CouponCategory::where('coupon_id', $coupon->id)->delete();
            
                // Insert new category mappings
                foreach ($request->category_ids as $category_id) {
                    CouponCategory::create([
                        'coupon_id' => $coupon->id,
                        'category_id' => $category_id,
                    ]);
                }
            } else {
                CouponCategory::where('coupon_id', $coupon->id)->delete();
            }
            
    
            DB::commit();
    
            return redirect()->back()->with(['message' => 'Coupon updated successfully', 'status' => true, 'code' => 200]);
    
        } catch (Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->with([
                'message' => 'Failed to update coupon: ' . $e->getMessage(),
                'status' => false,
                'code' => 500
            ]);
        }
    }
}
