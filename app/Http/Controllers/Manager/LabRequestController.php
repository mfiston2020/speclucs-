<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\UnavailableProduct;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LabRequestController extends Controller
{
    function index()
    {
        $lens_type  =   \App\Models\LensType::all();
        $index  =   \App\Models\PhotoIndex::all();
        $chromatics  =   \App\Models\PhotoChromatics::all();
        $coatings  =   \App\Models\PhotoCoating::all();
        // ==================

        $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('created_at', 'asc')->with('unavailableproducts')->with('client')->with('SoldProduct')->get();

        $unavailableProducts    =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('SoldProduct')->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        // priced
        $requests_priced  =   Invoice::where('company_id', userInfo()->company_id)->whereIn('status', ['priced', 'confirmed'])->orderBy('created_at', 'desc')->with('client')->with('unavailableproducts')->with('SoldProduct')->get();

        // sent to supplier
        $requests_supplier  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'sent to supplier')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();

        // sent to supplier
        $requests_lab  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('soldproduct')->with('client')->with('SoldProduct')->get();
        


        return view('manager.lab-request.index', compact('requests', 'products', 'unavailableProducts', 'suppliers', 'requests_priced', 'requests_supplier', 'requests_lab', 'lens_type', 'index', 'chromatics', 'coatings'));
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
        $lens_type  =   \App\Models\LensType::all();
        $index  =   \App\Models\PhotoIndex::all();
        $chromatics  =   \App\Models\PhotoChromatics::all();
        $coatings  =   \App\Models\PhotoCoating::all();
        // ==================


        // sent to lab
        $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('SoldProduct')->get();

        // in production
        $requests_inProduction   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('SoldProduct')->get();

        // completed
        $requests_completed   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('SoldProduct')->get();

        // delivered
        $requests_delivered  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('SoldProduct')->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

        return view('manager.lab-request.lab', compact('requests', 'requests_completed', 'requests_delivered', 'requests_inProduction', 'products', 'lens_type', 'index', 'chromatics', 'coatings'));
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

    function addpriceRequest(Request $request)
    {
        foreach ($request->prodId as $key => $value) {
            $newProduct  =   UnavailableProduct::find($value)->update([
                'cost'      =>  $request->cost[$key],
                'price'     =>  $request->price[$key],
                'location'  =>  $request->location[$key],
                'supplier'  =>  $request->supplier[$key],
                'lens_stock' => 0
            ]);

            $order      =   UnavailableProduct::find($value);

            $productRepo =   new ProductRepo();
            $newProduct = $productRepo->saveProduct($order->toArray(), '1', $order->toArray(), true);
        }

        Invoice::find($request->invoiceID)->update([
            'status' => 'priced'
        ]);

        return redirect()->back()->with('successMsg', 'Order price set!');
    }

    function requestConfirmPayment($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'Confirmed',
        ]);

        return redirect()->back()->with('successMsg', 'Order Payment confirmed');
    }

    function sendRequestToSupplier(Request $request)
    {
        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'sent to supplier',
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request Sent To Supplier!');
        }
    }

    function sendRequestTolab(Request $request)
    {
        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'sent to lab',
                    'sent_to_lab' => now(),
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request sent to Lab!');
        }
    }
}
