<?php

namespace App\Http\Controllers\User;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\CheckoutSession;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\ProductCart;

class InvoiceController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user_id = $request->header('id');
        $customer_details = CustomerProfile::where('user_id', $user_id)->first();
       

        // Get checkout session for the user
        $checkoutSession = CheckoutSession::where('user_id', $user_id)->first();

        if (!$checkoutSession) {
            return response()->json(['message' => 'No checkout session found'], 400);
        }

        // Decode selected cart items
        $cartItems = json_decode($checkoutSession->selected_cart_items, true);

        if (!$cartItems || count($cartItems) === 0) {
            return response()->json(['message' => 'No products selected for checkout'], 400);
        }

        // Start Database Transaction
        DB::beginTransaction();
        try {
            // Create Invoice
            $invoice = Invoice::create([
                'user_id' => $user_id,
                'total' => $checkoutSession->total,
                'vat' => 0,
                'discount' => 0,
                'shipping_charges' => 50,
                'coupon_code' => null,
                'coupon_discount' => 0,
                'grand_total' => $checkoutSession->total + 50,
                'customer_details' => $customer_details ? json_encode($customer_details) : null,
                'shipping_details' => $request->shipping_details ? json_encode($request->shipping_details) : null,
                'transection_id' => 'TXN' . time(),
                'validation_id' => 'VAL' . time(),
                'delivary_status' => 'pending',
                'payment_status' => 'pending',
            ]);            

            // Insert each product into invoice_products
            foreach ($cartItems as $item) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'user_id' => $user_id,
                    'admin_id' => $item['admin_id'] ?? null,
                    'vendor_id' => $item['vendor_id'] ?? null,
                ]);
            }

            // Remove products from Cart table
            ProductCart::where('user_id', $user_id)->whereIn('product_id', array_column($cartItems, 'product_id'))->delete();

            // Clear checkout session
            $checkoutSession->delete();

            // Commit Transaction
            DB::commit();

            return response()->json(['message' => 'Order placed successfully', 'invoice_id' => $invoice->id], 200);
        } catch (\Exception $e) {
            // Rollback Transaction if any error occurs
            DB::rollBack();
            return response()->json(['message' => 'Order placement failed', 'error' => $e->getMessage()], 500);
        }
    }
}
