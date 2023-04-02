<?php

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ClientsController extends Controller
{
    public function index()
    {
        $customers  =  \App\Models\Customer::where('company_id',Auth::user()->company_id)->get();
        return view('manager.customers.index',compact('customers'));
    }

    public function addClient()
    {
        return view('manager.customers.add');
    }

    public function saveClient(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
        ]);

        $customer   =   new \App\Models\Customer();

        $customer->company_id   =   Auth::user()->company_id;
        $customer->name         =   $request->name;
        $customer->phone        =   $request->phone;
        $customer->email        =   $request->email;
        // $customer->role         =   'client';
        // $customer->password     =   Hash::make(generateRandomString());

        try {
            $customer->save();
            return redirect()->route('manager.clients')->with('successMsg','Client Is Successfully Saved');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function editClients($id)
    {
        $detail =   \App\Models\Customer::find(Crypt::decrypt($id));
        return view('manager.customers.edit',compact('detail'));
    }

    public function updateClients(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
        ]);

        $id =   Crypt::decrypt($id);
        $customer   =   \App\Models\Customer::find($id);

        $customer->name     =   $request->name;
        $customer->email    =   $request->email;
        $customer->phone    =   $request->phone;

        try {
            $customer->save();
            return redirect()->route('manager.clients')->with('successMsg','Client Is Successfully Updated');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function deleteClients($id)
    {
        $detail =   \App\Models\Customer::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Client Is Successfully Removed');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }
}
