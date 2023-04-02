<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private $prms;
    public function __construct()
    {
        $this->middleware('manager');
        $this->middleware(function ($request, $next) {
            $this->prms = Auth::user()->permissions;
            if ($this->prms!='manager') {
                return redirect()->back()->with('warningMsg','you are not allowed to do this!');
            }
            return $next($request);
        });
    }


    public function index()
    {
        $user_info  =   \App\Models\User::find(Auth::user()->id);
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

        return view('manager.profile.settings',compact('user_info','company'));
    }

    public function supplierConfirm()
    {
        $user_info  =   \App\Models\User::find(Auth::user()->id);
        $state  =   0;
        if ($user_info->supplier_state==0)
        {
            $state  =   '1';
        }
        else{
            $state  =   '0';
        }
        $user_info->supplier_state =   $state;
        try {
            $user_info->save();
            return response()->json('success');
        }
        catch (\Throwable $th)
        {
            return response()->json('error');
        }
    }

    public function showStock()
    {
        $user_info  =   \App\Models\User::find(Auth::user()->id);
        $state  =   0;
        if ($user_info->show_stock==0)
        {
            $state  =   '1';
        }
        else{
            $state  =   '0';
        }
        $user_info->show_stock =   $state;
        try {
            $user_info->save();
            return response()->json('success');
        }
        catch (\Throwable $th)
        {
            return response()->json('error');
        }
    }
}
