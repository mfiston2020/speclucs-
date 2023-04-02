<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products       =   count(\App\Models\Product::all());
        $companies      =   count(\App\Models\CompanyInformation::all());
        $dcompanies     =   count(\App\Models\CompanyInformation::where('status','disabled')->get());
        // $clients        =   count(\App\Models\Product::all());

        return view('admin.dashboard',compact('products','companies','dcompanies'));
    }
}
