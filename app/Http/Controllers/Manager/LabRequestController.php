<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Power;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TrackStockRecord;
use App\Models\UnavailableProduct;
use App\Models\User;
use App\Repositories\ProductRepo;
use App\Repositories\StockTrackRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class LabRequestController extends Controller
{
    private $stocktrackRepo, $allProduct,$userDatail;

    public function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
        // dd($this->userDatail);
        // $this->middleware('auth');
        // $this->middleware(function ($request, $next) {
        //     $this->userDatail = Auth::user();
        //     // dd($this->userDatail);
        //     $this->allProduct   =   Product::where('company_id',$this->userDatail->company_id)->get();

        //     return $next($request);
        // });
    }
    // ===============

    function index()
    {
        $invoicess          =   Invoice::where('company_id', userInfo()->company_id)->orderBy('id', 'desc')->with('soldproduct')->get();

        $isOutOfStock       =   null;
        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        // ==================

        $requests   =   $invoicess->where('status', 'requested')->all();

        $bookings   =   $invoicess->where('status', 'booked')->all();

        $unavailableProducts          =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->with('unavailableproducts')->get();

        $products   =   Product::with('power')->where('company_id', userInfo()->company_id)->get();
        $powers     =   Power::where('company_id', userInfo()->company_id)->get();

        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        // priced
        $requests_priced    =   $invoicess->whereIn('status', ['priced', 'confirmed'])->all();

        // sent to supplier
        $requests_supplier  =   $invoicess->where('status', 'sent to supplier')->all();

        // sent to supplier
        $requests_lab       =   $invoicess->where('status', 'sent to lab')->all();

        // return $products;

        return view('manager.lab-request.index', compact('powers','requests', 'products', 'unavailableProducts', 'suppliers', 'requests_priced', 'requests_supplier', 'requests_lab', 'lens_type', 'index', 'chromatics', 'coatings','isOutOfStock','bookings'));
    }

    function sendToLab($id)
    {
        $soldProducts   =   Invoice::where('id', Crypt::decrypt($id))->with('soldproduct')->first();
        $products       =   Product::where('company_id', userInfo()->company_id)->get();

        // foreach ($soldProducts->soldproduct as  $sold) {
        //     $product    =   $products->where('id', $sold->product_id)->first();

        //     if ($product->stock <= 0) {
        //         return redirect()->back()->with('warningMsg', $product->product_name . ' | ' . $product->description . ' is out of Stock!');
        //     }
        // }

        // dd($soldProducts->soldproduct);

        foreach ($soldProducts->soldproduct as $key => $sold) {
            $allProduct =   Product::where('company_id', userInfo()->company_id)->get();
            $product    =   $allProduct->where('id', $sold->product_id)->first();

            $prdt = Product::where('id', $sold->product_id)->first();
            $prdt->stock    =   $prdt->stock < 1 ? 0 : $prdt->stock - 1;
            $prdt->save();

            // $stockVariation = $product->stock - 1;
            $this->stocktrackRepo->saveTrackRecord($prdt->id, $prdt->stock + 1, '1', $prdt->stock, 'sent to lab', 'rm', 'out');
        }

        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'sent to lab',
            'sent_to_lab' => now(),
        ]);

        return redirect()->back()->with('successMsg', 'Order sent to Lab!');
    }

    // ====================
    function receiveOrder()
    {
        $lens_type  =   \App\Models\LensType::all();
        $index      =   \App\Models\PhotoIndex::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        // ==================


        // sent to lab
        $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        // in production
        $requests_inProduction   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        // completed
        $requests_completed   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        // delivered
        $requests_delivered  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        // other products
        $other_orders  =   Invoice::where('company_id', userInfo()->company_id)->whereNotIn('status', ['delivered', 'sent to lab', 'in production', 'completed'])->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        $products   =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

        return view('manager.lab-request.lab', compact('requests', 'requests_completed', 'requests_delivered', 'other_orders', 'requests_inProduction', 'products', 'lens_type', 'index', 'chromatics', 'coatings'));
    }

    // ========================================
    function sendToProduction(Request $request)
    {
        $allProduct =   Product::where('company_id',userInfo()->company_id)->get();
        $invoice    =   Invoice::where('id', Crypt::decrypt($request->idsalfjei))->with('soldproduct')->first();

        $invoice->update([
            'status' => 'in production',
            'received_by_lab' => now()
        ]);

        // recording work in progress stock in
        foreach ($invoice->soldproduct as $key => $sold) {
            $product    =   $allProduct->where('id', $sold->product_id)->first();
            $stockVariation = $product->stock - 1;

            $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, '1', '0', 'in production', 'wip', 'in');
        }

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
        $allProduct =   Product::where('company_id', userInfo()->company_id)->get();
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                $invoice    =   Invoice::where('id', $value)->with('soldproduct')->first();
                Invoice::find($value)->update([
                    'status' => 'delivered',
                    'sent_to_seller' => now(),
                ]);

                foreach ($invoice->soldproduct as $key => $sold) {
                    $product    =   $allProduct->where('id', $sold->product_id)->first();
                    // $stockVariation = $product->stock - 1;

                    $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, '1', '0', 'delivered', 'wip', 'out');
                }
            }
            return redirect()->back()->with('successMsg', 'Request Delivered!');
        }
    }

    function receiveRequest(Request $request)
    {
        $allProduct =   Product::where('company_id', userInfo()->company_id)->get();
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                $invoice    =   Invoice::where('id', $value)->with('soldproduct')->first();
                Invoice::find($value)->update([
                    'status' => 'received',
                    'received_by_seller' => now(),
                ]);

                foreach ($invoice->soldproduct as $key => $sold) {
                    $product    =   $allProduct->where('id', $sold->product_id)->first();
                    // $stockVariation = $product->stock - 1;

                    $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, '1', '0', 'received', 'fg', 'in');
                }
            }
            return redirect()->back()->with('successMsg', 'Request Received!');
        }
    }

    function dispenseRequest(Request $request)
    {
        $allProduct =   Product::where('company_id', userInfo()->company_id)->get();
        if ($request->requestid == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestid as $key => $value) {
                $invoice    =   Invoice::where('id', $value)->with('soldproduct')->first();
                Invoice::find($value)->update([
                    'status' => 'collected',
                    'received_by_patient' => now(),
                ]);

                foreach ($invoice->soldproduct as $key => $sold) {
                    $product    =   $allProduct->where('id', $sold->product_id)->first();
                    // $stockVariation = $product->stock - 1;

                    $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, '1', '0', 'client reception', 'fg', 'out');
                }
            }
            return redirect()->back()->with('successMsg', 'Request Collected!');
        }
    }

    function addpriceRequest(Request $request)
    {
        // return $request->all();
        $invoice    =   Invoice::where('id', $request->invoiceID)->pluck('hospital_name')->first();
        foreach ($request->prodId as $key => $value) {
            $newProduct  =   UnavailableProduct::find($value)->update([
                'cost'      =>  $request->cost[$key],
                'price'     =>  $request->price[$key],
                'location'  =>  $request->location[$key],
                'supplier'  =>  $request->supplier[$key],
                'lens_stock' => 0
            ]);

            // $order      =   UnavailableProduct::find($value);

            // $productRepo =   new ProductRepo();
            // $newProduct = $productRepo->saveProduct($order->toArray(), '1', $order->toArray(), true);
        }

        Invoice::find($request->invoiceID)->update([
            'status' => $invoice != null ? 'Confirmed' : 'priced',
            'set_price' => now(),
        ]);

        return redirect()->back()->with('successMsg', 'Order price set!');
    }

    function requestConfirmPayment($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'Confirmed',
            'payment_approval' => now(),
        ]);

        return redirect()->back()->with('successMsg', 'Order Payment confirmed');
    }

    function requestCancelPayment($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'Canceled',
            'canceled' => now(),
        ]);

        return redirect()->back()->with('successMsg', 'Order Canceled!');
    }

    function sendRequestToSupplier(Request $request)
    {
        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $value) {
                Invoice::find($value)->update([
                    'status' => 'sent to supplier',
                    'sent_to_supplier' => now(),
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request Sent To Supplier!');
        }
    }

    function sendRequestTolab(Request $request)
    {
        $productRepo    =   new ProductRepo();

        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $value) {

                $products   = Invoice::where('id', $value)->with('soldproduct')->with('unavailableproducts')->first();

                foreach ($products->unavailableproducts as  $sold) {
                    $newProduct = $productRepo->saveUnavailableToStock($sold->toArray());

                    $prdt    =   Product::find($newProduct->id);

                    $prdt->stock = 1;
                    $prdt->save();

                    $sold->update([
                        'product_id' => $prdt->id,
                    ]);

                    // $this->stocktrackRepo->saveTrackRecord($prdt->id, 1, '1', 0, 'sent to lab', 'rm', 'out');
                    $this->stocktrackRepo->saveTrackRecord($prdt->id, 0, '1', 1, 'received from supplier', 'rm', 'in');
                }

                $this->sendToLab(Crypt::encrypt($value));

                // foreach ($products->soldproduct as  $sold) {
                //     $product    =   $this->allProduct->where('id', $sold->product_id)->first();

                //     if ($product->stock <= 0) {
                //         return redirect()->back()->with('warningMsg', $product->product_name . ' | ' . $product->description . ' is out of Stock!');
                //     }
                //     else{
                //         $this->sendToLab();
                //     }
                // }

                // Invoice::find($value)->update([
                //     'status' => 'sent to lab',
                //     'sent_to_lab' => now(),
                //     'receive_from_supplier' => now(),
                // ]);
            }
            return redirect()->back()->with('successMsg', 'Request sent to Lab!');
        }
    }
}
