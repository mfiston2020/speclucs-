<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $product    =   DB::table('sold_products')->select(DB::raw('sum(quantity) as sold, product_id'))
                                                    ->where('company_id',Auth::user()->company_id)
                                                    ->groupBy('product_id')
                                                    ->limit(5)
                                                    ->orderBy('sold','DESC')
                                                    ->get();

        $payment_method   =   DB::table('transactions')->select(DB::raw('sum(amount) as expenses, payment_method_id'))
                                                        ->where('company_id',Auth::user()->company_id)
                                                        ->groupBy('payment_method_id')
                                                        ->where('type','=','expense')
                                                        ->limit(5)
                                                        ->orderBy('expenses','ASC')
                                                        ->get();

        $expenses   =   \App\Models\Transactions::where(['type'=>'expense'])->where('company_id',Auth::user()->company_id)->select('*')->orderBy('amount','DESC')->limit(5);
        $expenses_count   =   count(\App\Models\Transactions::where(['type'=>'expense'])->where('company_id',Auth::user()->company_id)->select('*')->get());


        $customerInvoices    =   \App\Models\Invoice::where('client_id','<>','')->where('company_id',Auth::user()->company_id)
                                    ->where('payment','=',NULL)->limit(5)->orderBy('total_amount','DESC')->get();

        return view('manager.dashboard',compact('product','expenses','payment_method','expenses_count','customerInvoices'));
    }

    public function all_invoice()
    {
        return view('manager.invoices');
    }
}
