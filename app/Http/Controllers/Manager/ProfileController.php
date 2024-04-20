<?php

namespace App\Http\Controllers\Manager;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use App\Models\Country;
use App\Models\SupplierNotify;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function index()
    {
        $countries  =   Country::all();
        $user_info  =   \App\Models\User::find(Auth::user()->id);
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
        $suppliers  =   CompanyInformation::whereNull('is_vision_center')->where('can_supply','1')->whereNot('id',userInfo()->company_id)->where('status','active')->where('country_id',$user_info->company->country_id)->get();

        return view('manager.profile.index',compact('user_info','company','countries','suppliers'));
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
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
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
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        }
        else
        {
            return redirect()->back()->with('warningMsg','Old Password is not valid');
        }
    }

    public function company(Request $request)
    {
        $this->validate($request,[
            'company_name'=>'required',
            'company_number'=>'required',
            'company_email'=>'required',
        ]);

        $slug   =   str_slug($request->company_name);


        // else {
        //     return "something";
        // }

        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

        // ====================== Creating and inserting mentor picture in the folder ===============
        if($request->hasfile('company_logo'))
        {
            $company_logo   =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
            $companyLogo   =   $company_logo->logo;

            if($company_logo->logo!=null)
            {
                $image_path = "documents/logos/".$company_logo->logo;  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }


            $currentDate    =   Carbon::now()->toDateString();
            $companyLogo      =   $slug .'-'. $currentDate .'-'. uniqid() .'.'. $request->file('company_logo')->getClientOriginalExtension();

            if (!file_exists('documents/logos/'))
            {
                mkdir('documents/logos/',755,true);
            }
            $request->file('company_logo')->move('documents/logos/',$companyLogo);
            $company->logo                  =   $companyLogo;
        }


        $company->company_name          =   $request->company_name;
        $company->company_phone         =   $request->company_number;
        $company->company_email         =   $request->company_email;
        $company->company_street        =   $request->company_street;
        $company->company_tin_number    =   $request->company_tin_number;
        $company->country_id            =   $request->country;
        $company->currency              =   $request->currency;



        try
        {
            $company->save();
            return redirect()->back()->with('successMsg','Company Information Successfully Updated');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function sms_message(Request $request)
    {
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

        $company->sms_message   =   $request->message;

        try
        {
            $company->save();
            return redirect()->back()->with('successMsg','Message Saved');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    function bankDetail(Request $request)
    {
        $company    =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

        $company->bank_name =   $request->bank_name;
        $company->account_name  =   $request->account_name;
        $company->account_number    =   $request->account_number;
        $company->swift_code    =   $request->swift_code;

        try
        {
            $company->save();
            return redirect()->back()->with('successMsg','Bank details updated!');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    function request_supply($id){

        try
        {
            SupplyRequest::create([
                'user_id'       =>  userInfo()->id,
                'supplier_id'   =>  decrypt($id),
                'request_from'  =>  userInfo()->company_id,
            ]);

            $notification   =   new SupplierNotify();
            $notification->company_id   =   Auth::user()->company_id;
            $notification->supplier_id  =   decrypt($id);
            $notification->notification =   'New Supply Request';
            $notification->save();

            return redirect()->back()->with('successMsg','Supply Request sent the button will change green on confirmation!');
        }
        catch (\Throwable $th){
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    function request_supply_reply($id,$reply){

        SupplyRequest::where('id',decrypt($id))->update([
            'status'=>$reply
        ]);

        $notification   =   new SupplierNotify();
        $notification->company_id   =   Auth::user()->company_id;
        $notification->supplier_id  =   decrypt($id);
        $notification->notification =   'Your Request was '.$reply;
        $notification->save();


        return redirect()->back()->with('successMsg','Request was '.$reply.'!');
    }
}
