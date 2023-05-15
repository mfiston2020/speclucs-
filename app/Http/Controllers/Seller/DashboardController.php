<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $products   =   count(\App\Models\Product::all());
        // return view('seller.dashboard',compact('products'));
        return view('seller.dashboard');
    }

    public function all_invoice()
    {
        return view('seller.invoices');
    }
}
