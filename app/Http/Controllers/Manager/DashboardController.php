<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_product_cost =   0;
        $total_product_cost =   0;
        $total_prod         =   [];
        $earning            =   0;
        $totalValue         =   0;
        $totalQuantity      =   0;


        $soldproducts   =   SoldProduct::where('company_id', userInfo()->company_id)->with('product', function ($query) {
            $query->select('id', 'cost', 'price', 'stock');
        })->select('product_id', 'quantity', 'total_amount')->whereYear('created_at', date('Y'))->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->select('id', 'description', 'product_name', 'cost', 'price', 'stock')->get();
        $invoices   =   \App\Models\Invoice::where('company_id', Auth::user()->company_id)->count();
        $suppliers  =   \App\Models\Supplier::where('company_id', Auth::user()->company_id)->count();

        foreach ($soldproducts as $key => $sold) {
            $total_product_cost +=  (float)$sold->product->cost * (float)$sold->quantity;

            $income     =   (float)$sold->total_amount - ((float)$sold->quantity * ((float)$sold->product->cost));
            $earning    =  $earning + $income;
        }

        foreach ($products as $key => $product) {
            if (is_numeric($product->cost) && is_numeric($product->stock)) {
                $amount     =   $product->cost * $product->stock;
                $totalValue =   $totalValue + $amount;
            }
        }

        $product    =   DB::table('sold_products')->select(DB::raw('sum(quantity) as sold, product_id'))
            ->where('company_id', Auth::user()->company_id)
            ->groupBy('product_id')
            ->limit(5)
            ->orderBy('sold', 'DESC')
            ->get();

        $payment_method   =   DB::table('transactions')->select(DB::raw('sum(amount) as expenses, payment_method_id'))
            ->where('company_id', Auth::user()->company_id)
            ->groupBy('payment_method_id')
            ->where('type', '=', 'expense')
            ->limit(5)
            ->orderBy('expenses', 'ASC')
            ->get();

        $expenses   =   \App\Models\Transactions::where(['type' => 'expense'])->where('company_id', Auth::user()->company_id)->select('*')->orderBy('amount', 'DESC')->limit(5);

        $expenses_count   =   count(\App\Models\Transactions::where(['type' => 'expense'])->where('company_id', Auth::user()->company_id)->select('*')->get());


        $customerInvoices    =   \App\Models\Invoice::where('client_id', '<>', '')->where('company_id', Auth::user()->company_id)
            ->where('payment', '=', NULL)->limit(5)->orderBy('total_amount', 'DESC')->get();

        return view('manager.dashboard', compact('product', 'products', 'expenses', 'payment_method', 'expenses_count', 'customerInvoices', 'total_product_cost', 'invoices', 'suppliers', 'earning', 'totalValue'));
    }

    public function all_invoice()
    {
        return view('manager.invoices');
    }
}
