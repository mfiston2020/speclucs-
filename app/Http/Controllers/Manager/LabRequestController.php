<?php

namespace App\Http\Controllers\Manager;

use App\Models\Invoice;
use App\Models\Power;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Repositories\ProductRepo;
use App\Models\UnavailableProduct;
use App\Http\Controllers\Controller;
use App\Models\LensType;
use App\Repositories\StockTrackRepo;
use Illuminate\Support\Facades\Crypt;

class LabRequestController extends Controller
{
    private $stocktrackRepo, $allProduct,$userDatail;

    public function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
    }
    // ===============

    function index()
    {
        $invoicess          =   Invoice::where('company_id', userInfo()->company_id)->orderBy('id', 'desc')->with('soldproduct')->paginate(50);

        $isOutOfStock       =   null;
        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();
        // ==================

        $bookings   =   $invoicess->where('status', 'booked')->all();

        $requests   =   $invoicess->where('status', 'requested')->all();

        $products   =   Product::where('company_id', userInfo()->company_id)->get();
        $powers     =   Power::where('company_id', userInfo()->company_id)->get();

        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        // priced
        $requests_priced    =   $invoicess->whereIn('status', ['priced', 'Confirmed'])->all();


        // sent to supplier
        $requests_supplier  =   $invoicess->where('status', 'sent to supplier')->all();

        // sent to supplier
        $requests_lab       =   $invoicess->where('status', 'sent to lab')->all();

        // return $products;
        return view('manager.lab-request.index', compact('invoicess','powers','requests', 'products', 'suppliers', 'requests_priced', 'requests_supplier', 'requests_lab', 'lens_type', 'index', 'chromatics', 'coatings','isOutOfStock','bookings'));
    }

    function indexWithTye($type){
        $invoices          =   Invoice::where('company_id', userInfo()->company_id)->orderBy('id', 'desc')->with('soldproduct')->get();

        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();

        if ($type=='requested') {
            $invoicess  =   $invoices->where('status','requested')->all();

            return view('manager.lab-request.requested',compact('invoicess','lens_type', 'index', 'chromatics', 'coatings'));
        }

        if ($type=='booking') {

            $bookings   =   $invoices->where('status', 'booked')->all();

            return view('manager.lab-request.booking',compact('bookings','lens_type', 'index', 'chromatics', 'coatings'));
        }

        if ($type=='priced') {

            // priced
            $requests_priced    =   $invoices->whereIn('status', ['priced', 'Confirmed'])->all();

            return view('manager.lab-request.priced',compact('requests_priced','lens_type', 'index', 'chromatics', 'coatings'));
        }

        if ($type=='po-sent') {
        // sent to supplier
        $requests_supplier  =   $invoices->where('status', 'sent to supplier')->all();

        // sent to supplier
        $requests_lab       =   $invoices->where('status', 'sent to lab')->all();
            return view('manager.lab-request.po-sent',compact('requests_lab','requests_supplier','lens_type', 'index', 'chromatics', 'coatings'));
        }
    }

    function naOrders(){


        $isOutOfStock       =   null;
        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();



        $requests          =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->paginate(50);

        // dd($requests);

        $products   =   Product::where('company_id', userInfo()->company_id)->get();
        $powers     =   Power::where('company_id', userInfo()->company_id)->get();

        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        return view('manager.lab-request.unavailable',compact('requests','powers','requests', 'products', 'suppliers','lens_type', 'index', 'chromatics', 'coatings'));
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
            // $prdt->save();

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
        $f_product_id   =   null;
        $productRepo    =   new ProductRepo();
        $lensType       =   LensType::all();
        $powers         =   Power::where('company_id',userInfo()->company_id)->get();
        $allProduct     =   Product::where('company_id', userInfo()->company_id)->get();

        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $value) {

                $products   = Invoice::where('id', $value)->with('soldproduct')->with('unavailableproducts')->first();

                foreach ($products->unavailableproducts as $count=>  $sold) {
                    $lenT   =   $lensType->where('id',$sold->type_id)->pluck('name')->first();

                    if (initials($lenT)=='SV') {
                        $f_product_id   =   $powers->where('type_id',$sold->type_id)->where('chromatics_id',$sold->chromatic_id)->where('coating_id',$sold->coating_id)->where('sphere',format_values($sold->sphere))->where('cylinder',format_values($sold->cylinder))->where('axis',format_values($sold->axis))->where('add',format_values($sold->addition))->first();

                        // if ($count>0) {
                            // dd($f_product_id);
                        // }
                    } else {
                        $f_product_id   =   $powers->where('type_id',$sold->type_id)->where('chromatics_id',$sold->chromatic_id)->where('coating_id',$sold->coating_id)->where('sphere',format_values($sold->sphere))->where('cylinder',format_values($sold->cylinder))->where('axis',format_values($sold->axis))->where('add',format_values($sold->addition))->first();
                        dd('hi');
                    }


                    if ($f_product_id!=null)
                    {
                        // dd('kjhgfds');
                        $sold->update([
                            'product_id' => $f_product_id->id,
                        ]);

                        $prdt    =   Product::find($f_product_id->id);

                        $sto    =   $prdt->stock;
                        $prdt->stock = $sto+1;

                        // dd($prdt->stock);
                        $prdt->save();

                        $this->stocktrackRepo->saveTrackRecord($f_product_id->id, $prdt->stock, '1', 1, 'received from supplier', 'rm', 'in');
                        continue;
                    }
                    else{
                        if ($f_product_id) {
                            continue;
                        }

                        $newProduct = $productRepo->saveUnavailableToStock($sold->toArray());

                        $prdt    =   Product::find($newProduct->id);

                        $prdt->stock = 1;
                        $prdt->save();

                        $sold->update([
                            'product_id' => $prdt->id,
                        ]);

                        $this->stocktrackRepo->saveTrackRecord($prdt->id, 0, '1', 1, 'received from supplier', 'rm', 'in');
                    }
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
