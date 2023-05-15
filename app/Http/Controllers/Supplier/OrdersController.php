<?php

namespace App\Http\Controllers\Supplier;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->get();
        return view('supplier.order.index',compact('orders'));
    }
}
