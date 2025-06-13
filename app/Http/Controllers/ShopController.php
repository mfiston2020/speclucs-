<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    function index(){
        $categories =   Category::withCount('products')->get();
        $products   =   Product::whereNotNull('picture')->orderby('created_at','desc')->paginate(100);
        return view('website.index',compact('products','categories')); 
    }
}
