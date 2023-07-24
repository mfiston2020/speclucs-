<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderInvoicesController extends Controller
{
    public function myOrders()
    {
        $invoices    =   \App\Models\OrderInvoice::where('company_id',Auth::user()->company_id)->get();
        return view('manager.orderInvoice.myinvoice',compact('invoices'));
    }

    public function receivedOrders()
    {
        $myCustomers    =   array();
        $customers  =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->select('company_id')->get();
        foreach ($customers as $key => $value) {
            array_push($myCustomers,$value->company_id);
        }
        $customerList   =   array_unique($myCustomers);
        return view('manager.orderInvoice.received',compact('customerList'));
    }

    public function searchOrders(Request $request)
    {
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required',
            'company'=>'required',
        ]);

        $invoices    =   \App\Models\OrderInvoice::where('supplier_id',Auth::user()->company_id)->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))->where('company_id',$request->company)->get();

        if ($invoices->isEmpty())
        {
            return redirect()->back()->withInput()->with('warningMsg','No invoices found');
        }
        else
        {
            return view('manager.orderinvoice.receivedList',compact('invoices'));
        }
    }

    public function searchMyInvoice(Request $request)
    {
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required',
            'status'=>'required',
        ]);

        $invoices    =   \App\Models\OrderInvoice::where('company_id',Auth::user()->company_id)->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                                                  ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                                                  ->where('status',$request->status)->get();
        $status =   $request->status;

        if ($invoices->isEmpty())
        {
            return redirect()->back()->withInput()->with('warningMsg','No invoices found');
        }
        else
        {
            return view('manager.orderinvoice.myInvoiceList',compact('invoices','status'));
        }
    }

    public function payMyInvoice(Request $request)
    {
        if (!$request->invoice)
        {
            return redirect()->back()->with('warningMsg','Please select at least one invoice!');
        }
        else
        {
            for ($i=0; $i < count($request->invoice); $i++)
            {
                $statement_invoice  =   \App\Models\OrderInvoice::find($request->invoice[$i]);
                $statement_invoice->status  =   'paid';
                $statement_invoice->save();
            }
        }

        return redirect()->route('manager.my.order.invoice')->with('successMsg','Invoices Successfully paid!');
    }

    public function trackOrder()
    {
        return view('manager.myorder.trackForm');
    }

    public function trackingOrder(Request $request)
    {
        $order  =   \App\Models\Order::where('order_number',$request->order_number)->first();
        $order_number  =   $request->order_number;

        if (!$order) {
            return redirect()->back()->with('warningMsg','Order Number not found!');
        } else {
            return view('manager.myorder.track',compact('order','order_number'));
        }


    }
}
