<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Illuminate\Support\Facades\Crypt;

class CompaniesController extends Controller
{
    public function index(){

        $companies  =   \App\Models\CompanyInformation::all();
        // return $companies;
        return view('admin.company.index',compact('companies'));
    }

    public function add()
    {
        return view('admin.company.create');
    }

    public function save(Request $request)
    {
        // return 'hello';
        $this->validate($request,[
            'company_name'=>'required',
            'company_email'=>'required',
            'company_phone'=>'required  | unique:company_information,company_email',
            'company_street'=>'required',
            'tin_number'=>'required',
            'director_name'=>'required',
            'director_email'=>'required | unique:users,email',
            'director_phone'=>'required',
        ]);

        $company    =   new \App\Models\CompanyInformation();

        $company->company_name  =   $request->company_name;
        $company->company_phone  =   $request->company_phone;
        $company->company_email  =   $request->company_email;
        $company->company_street  =   $request->company_street;
        $company->company_tin_number  =   $request->tin_number;

        try {
            $company->save();

            $user   =   new \App\Models\User();
            $id     =   $company->id;

            $user->company_id   =   $id;
            $user->name   = $request->director_name;
            $user->email   =   $request->director_email;
            $user->role   =   'manager';
            $user->phone   =   $request->director_phone;
            $user->password   =   Hash::make($request->director_phone);


            try {
                $user->save();
                return redirect()->route('admin.companies')->with('successMsg','The Company has been successfully Recoded!');
            } catch (\Throwable $th) {
                return redirect()->back()->with('errorMsg','Something went wrong!' .$th);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!'.$th);
        }

    }

    public function deactivate($id)
    {
        $users  =   \App\Models\User::where(['company_id'=>Crypt::decrypt($id)])->select('*')->get();
        $company=   \App\Models\CompanyInformation::find(Crypt::decrypt($id));

        foreach ($users as $key => $user) {
            $user->status   =   'disabled';
            $user->save();
        }

        $company->status    =   'disabled';
        
        try 
        {
            $company->save();
            return redirect()->back()->with('successMsg','The Company has been successfully De Activated!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!' .$th);
        }
    }

    public function activate($id)
    {
        $users  =   \App\Models\User::where(['company_id'=>Crypt::decrypt($id)])->select('*')->get();
        $company=   \App\Models\CompanyInformation::find(Crypt::decrypt($id));

        foreach ($users as $key => $user) {
            $user->status   =   'active';
            $user->save();
        }

        $company->status    =   'active';
        
        try 
        {
            $company->save();
            return redirect()->back()->with('successMsg','The Company has been successfully Activated!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!' .$th);
        }
    }

    // ==========================

    public function allowsms(Request $request)
    {
        $company  =   \App\Models\CompanyInformation::find($request->company_id);

        $clinic_state   =   $request->clinic;
        $sms_state      =   $request->sms;
        $sms            =   (int)$company->sms_quantity + (int)$request->additional_sms;

        if ($clinic_state) 
        {
            $clinic_state  =   '1';
        }
        else{
            $clinic_state  =   '0';
        }

        if ($sms_state) 
        {
            $sms_state  =   '1';
        }
        else{
            $sms_state  =   '0';
        }

        $company->is_clinic =   $clinic_state;
        $company->can_send_sms  =   $sms_state;
        $company->sms_quantity  =   $sms;
        try 
        {
            $company->save();
            return redirect()->back()->with('successMsg','The Company Settings has been successfully Updated!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!' .$th);
        }
    }

    public function settings($id)
    {
        $company    =   \App\Models\CompanyInformation::FindOrFail(Crypt::decrypt($id));
        return view('admin.company.settings',compact('company'));
    }
}