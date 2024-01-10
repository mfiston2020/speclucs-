<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivedOrdersController extends Controller
{
    public function index()
    {
        $orders             =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','<>','canceled')->select('*')->get();
        $orders_count       =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','<>','canceled')->select('*')->get());
        $orders_new         =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','submitted')->select('*')->get());
        $orders_production  =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','production')->select('*')->get());
        $orders_completed   =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','completed')->select('*')->get());
        $orders_delivery    =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','delivery')->select('*')->get());
        $orders_received    =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','received')->select('*')->get());

        $orders_received    =   count(\App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','received')->select('*')->get());
        $orders_dlvry       =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','delivery')->select('*')->get();

        return view('manager.receivedorder.index',compact('orders','orders_count','orders_new','orders_production','orders_completed','orders_delivery','orders_received'));
    }

    function orders($query){
        
    }

    public function new()
    {
        $orders =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','submitted')->get();
        return view('manager.receivedorder.new',compact('orders'));
    }

    public function production(Request $request)
    {
        if (!$request->has('order'))
        {
            return redirect()->back()->with('warningMsg','Please select at least one Order!');
        }
        else
        {
            // if (!$request->delivery_date) {
                $orders_count   =   0;
                for ($i=0; $i < count($request->order); $i++)
                {
                    $order    =   \App\Models\Order::find($request->order[$i]);
                    $order->status   =   'production';
                    $order->production_date   =   now();
                    $order->expected_delivery   =   $request->delivery_date[$i];
                    $order->save();

                    $product        =   \App\Models\Product::find($order->product_id);
                    $product->stock =   $product->stock - $order->quantity;
                    $product->save();
                    $orders_count++;
                }
                return redirect()->route('manager.received.order')->with('successMsg',$orders_count.' Selected Orders now in production');
            // } else {
            //     return redirect()->back()->with('warningMsg','Please add the date!')->withInput();
            // }

        }
    }

    public function inProduction()
    {
        $orders =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','production')->get();
        return view('manager.receivedorder.inProduction',compact('orders'));
    }

    // ========= in completed ============
    public function completed(Request $request)
    {
        if (!$request->order)
        {
            return redirect()->back()->with('warningMsg','Please select at least one Order!');
        }
        else
        {
            for ($i=0; $i < count($request->order); $i++)
            {
                $order    =   \App\Models\Order::find($request->order[$i]);
                $order->status   =   'completed';
                $order->completed_date   =   now();
                $order->save();
            }
            return redirect()->route('manager.received.order')->with('successMsg','Selected Orders now Completed');
        }
    }

    public function incomplete()
    {
        $orders =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','completed')->get();
        return view('manager.receivedorder.completed',compact('orders'));
    }

    public function indelivery()
    {
        $orders =   \App\Models\Order::where('supplier_id',Auth::user()->company_id)->where('status','=','delivery')->get();
        return view('manager.receivedorder.delivery',compact('orders'));
    }
    public function delivery (Request $request)
    {
        if (!$request->order)
        {
            return redirect()->back()->with('warningMsg','Please select at least one Order!');
        }
        else
        {
            for ($i=0; $i < count($request->order); $i++)
            {
                $order    =   \App\Models\Order::find($request->order[$i]);
                // return $order;
                $order->status   =   'delivery ';
                $order->delivery_date   =   now();
                $order->save();

                // creating invoices for each select order
                // =======================================
                $invoice    =   new \App\Models\OrderInvoice();
                $invoice->invoice_number    =   $order->order_number;
                $invoice->order_id          =   $order->id;
                $invoice->cost              =   $order->order_cost;
                $invoice->company_id        =   $order->company_id;
                $invoice->supplier_id       =   $order->supplier_id;
                $invoice->save();
            }
            return redirect()->route('manager.received.order')->with('successMsg','Selected Orders now in Delivery');
        }
    }
}
