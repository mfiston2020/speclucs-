<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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
        $users  =   \App\Models\User::where('company_id',Auth::user()->company_id)->where('id','<>',Auth::user()->id)->get();
        return view('manager.users.index',compact('users'));
    }

    public function addUser()
    {
        return view('manager.users.create');
    }

    public function saveUser(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'permission'=>'required',
        ]);

        $user   =   new \App\Models\User();

        $user->company_id   =   Auth::user()->company_id;
        $user->name         =   $request->name;
        $user->email        =   $request->email;
        $user->role         =   'manager';
        $user->permissions  =   $request->permission;
        $user->phone        =   $request->phone;
        $user->password     =   Hash::make($request->phone);
        $user->status       =   'active';

        try {
            $user->save();
            return redirect()->route('manager.users')->with('successMsg','User Account Successfully Created');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!' );
        }
    }

    function editUser($id)
    {
        $user   =   \App\Models\User::find(Crypt::decrypt($id));
        return view('manager.users.edit',compact('user'));
    }

    public function updateUser($id, Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'permission'=>'required',
        ]);

        $user   =   \App\Models\User::find(Crypt::decrypt($id));

        $user->company_id   =   Auth::user()->company_id;
        $user->name         =   $request->name;
        $user->email        =   $request->email;
        $user->phone        =   $request->phone;
        $user->permissions  =   $request->permission;

        try {
            $user->save();
            return redirect()->route('manager.users')->with('successMsg','User Account Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function disableUser($id)
    {
        $user   =   \App\Models\User::find(Crypt::decrypt($id));

        $user->status   =   'disabled';

        try {
            $user->save();
            return redirect()->route('manager.users')->with('successMsg','User Account Successfully DeActivated');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function activateUser($id)
    {
        $user   =   \App\Models\User::find(Crypt::decrypt($id));

        $user->status   =   'active';

        try {
            $user->save();
            return redirect()->route('manager.users')->with('successMsg','User Account Successfully Activated');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
