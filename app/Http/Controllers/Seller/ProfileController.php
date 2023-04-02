<?php

namespace App\Http\Controllers\Seller;

use DB;
use File;
use Auth;
use Hash;
use Crypt;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    function index()
    {
        $user_info  =   \App\Models\User::find(Auth::user()->id);
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
        return view('seller.profile.index',compact('user_info','company'));
    }

    public function username(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required | email',
        ]);
        
        $user_info  =   \App\Models\User::find(Auth::user()->id);

        $user_info->name        =   $request->name;
        $user_info->email       =   $request->email;

        try 
        {
            $user_info->save();
            return redirect()->back()->with('successMsg','Information Successfully Updated');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    public function password(Request $request)
    {
        $this->validate($request,[
            'current_password'=>'required',
            'password'=>'required | confirmed | min:8',
        ]);
        
        $user_info  =   \App\Models\User::find(Auth::user()->id);

        if (Hash::check($request->current_password, $user_info->password)) 
        {
            $user_info->password        =   Hash::make($request->password);

            try 
            {
                $user_info->save();
                return redirect()->back()->with('successMsg','Password Successfully Updated');
            } 
            catch (\Throwable $th) 
            {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
            }
        }
        else
        {
            return redirect()->back()->with('warningMsg','Old Password is not valid');
        }
    }
}
