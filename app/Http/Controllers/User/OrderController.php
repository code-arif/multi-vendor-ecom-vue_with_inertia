<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //====================get all order =====================//
    public function getAllOrder(Request $request){
        $user_id = $request->header('id');
        $orders = Invoice::where('user_id', $user_id)->with('invoice_products.product')->get();
        // return $orders;exit();
        return Inertia::render('User/Order/OrderListPage',[
            'orders' => $orders,
        ]);
    }

    //===================order delete =====================//
    public function deleteOrder(Request $request, $id){
        $user_id = $request->header('id');
        $order_delete = Invoice::where('id', $id)->where('user_id', $user_id)->delete();
        if($order_delete){
            return redirect()->back()->with(['message' => 'Order deleted successfully. please continue shopping', 'status' => true]);
        }
    }
}
