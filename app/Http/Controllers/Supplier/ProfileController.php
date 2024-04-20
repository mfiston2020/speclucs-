<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    function index()
    {
        $user_info  =   \App\Models\User::find(Autf::user()->id);
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
        return view('supplier.profile.index',compact('user_info','company'));
    }
}
