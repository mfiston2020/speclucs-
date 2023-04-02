<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use Auth;

class NotificationsController extends Controller
{
    public function read($id)
    {
        $notification   =   \App\Models\SupplierNotify::find(Crypt::decrypt($id));
        return $notification;
    }

    public function clear()
    {
        $notification   =   \App\Models\SupplierNotify::where('supplier_id',Auth::user()->company_id)->get();
        foreach ($notification as $key => $noti) {
            $noti->status   =   '1';
            $noti->save();
        }
        return redirect()->back()->with('successMsg','Notifications cleared');
    }
}
