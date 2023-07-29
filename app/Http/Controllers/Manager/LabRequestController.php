<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LabRequestController extends Controller
{
    function index()
    {
        $from = 'seller';
        $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

        return view('manager.lab-request.index', compact('requests', 'products', 'from'));
    }

    function sendToLab($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'sent to lab',
            'sent_to_lab' => now(),
        ]);
        return redirect()->back()->with('successMsg', 'Request sent to Lab!');
    }

    // ====================
    function receiveOrder()
    {
        $from = 'lab';
        // sent to lab
        $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        // in production
        $requests_inProduction   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'in production')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        // completed
        $requests_completed   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'completed')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        // delivered
        $requests_delivered  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'delivered')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

        return view('manager.lab-request.lab', compact('requests', 'requests_completed', 'requests_delivered', 'requests_inProduction', 'products', 'from'));
    }

    // ========================================
    function sendToProduction(Request $request)
    {
        Invoice::find(Crypt::decrypt($request->idsalfjei))->update([
            'status' => 'in production',
            'received_by_lab' => now()
        ]);
        return redirect()->back()->with('successMsg', 'Request sent to Production!');
    }

    // ==============
    function sendToCompleted(Request $request)
    {
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'completed',
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request completed!');
        }
    }

    // ==============
    function sendToDelivered(Request $request)
    {
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'delivered',
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request completed!');
        }
    }

    function receiveRequest(Request $request)
    {
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'received',
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request Received!');
        }
    }

    function dispenseRequest(Request $request)
    {
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'collected',
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request Collected!');
        }
    }
}
