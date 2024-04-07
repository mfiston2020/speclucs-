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
use App\Models\TrackOrderRecord;
use App\Repositories\StockTrackRepo;
use Illuminate\Support\Facades\Crypt;

class LabRequestController extends Controller
{
    private $stocktrackRepo;

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
        $isOutOfStock   =   null;

        // dd($invoices[0]->supplier);

        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();

        if ($type=='requested') {

            $invoicess          =   Invoice::where('company_id', userInfo()->company_id)
                                            ->where('status','requested')
                                            ->orderBy('id','desc')
                                            ->whereDoesntHave('unavailableProducts')->get();

            $invoicess_out          =   Invoice::where('supplier_id', userInfo()->company_id)
                                            ->where('status','requested')
                                            ->orderBy('id','desc')
                                            ->whereDoesntHave('unavailableProducts')->get();

            return view('manager.lab-request.requested',compact('invoicess','invoicess_out','lens_type', 'index', 'chromatics', 'coatings','isOutOfStock'));
        }

        $invoices          =   Invoice::where('company_id', userInfo()->company_id)
                                        ->orderBy('id','desc')
                                        ->with('soldproduct')
                                        ->get();

        $invoices_out          =   Invoice::where('supplier_id', userInfo()->company_id)
                                        ->orderBy('id','desc')
                                        ->with('soldproduct')
                                        ->get();

        if ($type=='booking') {

            $bookings   =   $invoices->where('status', 'booked')->all();
            $bookings_out   =   $invoices_out->where('status', 'booked')->all();

            return view('manager.lab-request.booking',compact('bookings','bookings_out','lens_type', 'index', 'chromatics', 'coatings','isOutOfStock'));
        }

        if ($type=='priced') {

            // priced
            $requests_priced    =   $invoices->whereIn('status', ['priced', 'Confirmed'])->all();
            $requests_priced_out    =   $invoices_out->whereIn('status', ['priced', 'Confirmed'])->all();

            return view('manager.lab-request.priced',compact('requests_priced','requests_priced_out','lens_type', 'index', 'chromatics', 'coatings','isOutOfStock'));
        }

        if ($type=='po-sent') {
        // sent to supplier
        $requests_supplier  =   $invoices->where('status', 'sent to supplier')->all();
        $requests_supplier_count  =   $invoices_out->where('status', 'sent to supplier')->all();

        // sent to supplier
        // $requests_lab       =   $invoices->where('status', 'sent to lab')->all();
            return view('manager.lab-request.po-sent',compact('requests_supplier','requests_supplier_count','lens_type', 'index', 'chromatics', 'coatings','isOutOfStock'));
        }
    }

    function naOrders(){


        $isOutOfStock       =   null;
        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();



        $requests          =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->paginate(50);

        $requests_out          =   Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->paginate(50);


        // $products   =   Product::where('company_id', userInfo()->company_id)->get();
        // $powers     =   Power::where('company_id', userInfo()->company_id)->get();

        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        return view('manager.lab-request.unavailable',compact('requests','lens_type','requests_out', 'index', 'chromatics', 'coatings','suppliers'));
    }

    function sendToLab($id)
    {
        $soldProducts   =   Invoice::where('id', Crypt::decrypt($id))->with('soldproduct')->with('unavailableProducts')->first();

        // dd($soldProducts);

        foreach ($soldProducts->unavailableProducts as $key => $unavailable) {

            $prdt = Product::where('id', $unavailable->product_id)->first();

            $nmstock    =   $prdt->stock;
            $prdt->stock=   $nmstock - $unavailable->quantity;
            $prdt->save();

            $this->stocktrackRepo->saveTrackRecord($prdt->id, $nmstock, $unavailable->quantity, $prdt->stock, 'sent to lab', 'rm', 'out');
        }

        foreach ($soldProducts->soldproduct as $sold) {

            $prdt = Product::where('id', $sold->product_id)->first();
            $nmstock    =   $prdt->stock;
            $prdt->stock=   $nmstock - $sold->quantity;
            $prdt->save();

            $this->stocktrackRepo->saveTrackRecord($prdt->id, $nmstock, $sold->quantity, $prdt->stock, 'sent to lab', 'rm', 'out');
        }

        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'sent to lab',
            'sent_to_lab' => now(),
        ]);

        TrackOrderRecord::create([
            'status'        =>  'sent to lab',
            'user_id'       =>  auth()->user()->id,
            'invoice_id'    =>  Crypt::decrypt($id),
        ]);

        return redirect()->back()->with('successMsg', 'Order sent to Lab!');
    }

    // ====================
    function receiveOrder($type)
    {
        // $lens_type  =   \App\Models\LensType::all();
        // $index      =   \App\Models\PhotoIndex::all();
        // $chromatics =   \App\Models\PhotoChromatics::all();
        // $coatings   =   \App\Models\PhotoCoating::all();
        // ==================


        if (decrypt($type)=='new') {
            // sent to lab
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            } else {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                $query->with('product',function($q){
                    $q->with(['power','category']);
                });
            })->paginate(100);
            }


            // sent to lab
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests_out   =   Invoice::where('company_id', userInfo()->company_id)->whereNotNull('supplier_id')->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }else{
                $requests_out   =   Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }

            return view('manager.lab-request.received.new', compact('requests','requests_out'));
        }

        if (decrypt($type)=='production') {
            // in production
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            } else {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                $query->with('product',function($q){
                    $q->with(['power','category']);
                });
            })->paginate(100);
            }


            // in production
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests_out   =   Invoice::where('company_id', userInfo()->company_id)->whereNotNull('supplier_id')->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }else{
                $requests_out   =   Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }
            // $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'in production')->orderBy('created_at', 'desc')->with('unavailableproducts','client','soldproduct')->paginate(100);

            return view('manager.lab-request.received.in-production', compact('requests','requests_out'));
        }

        if (decrypt($type)=='completed') {
            // completed
            // $requests   =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts','client','soldproduct')->paginate(100);


            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            } else {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                $query->with('product',function($q){
                    $q->with(['power','category']);
                });
            })->paginate(100);
            }


            // completed
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests_out   =   Invoice::where('company_id', userInfo()->company_id)->whereNotNull('supplier_id')->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }else{
                $requests_out   =   Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'completed')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }

            return view('manager.lab-request.received.completed', compact('requests','requests_out'));
        }

        if (decrypt($type)=='delivered') {
            // delivered
            // $requests  =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts','client','soldproduct')->paginate(100);

            // sent to lab
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            } else {
                $requests   =   Invoice::where('company_id', userInfo()->company_id)->whereNull('supplier_id')->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                $query->with('product',function($q){
                    $q->with(['power','category']);
                });
            })->paginate(100);
            }


            // delivered
            if (getuserCompanyInfo()->is_vision_center=='1') {
                $requests_out   =   Invoice::where('company_id', userInfo()->company_id)->whereNotNull('supplier_id')->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }else{
                $requests_out   =   Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'delivered')->orderBy('created_at', 'desc')->with('unavailableproducts')->with('soldproduct',function($query){
                    $query->with('product',function($q){
                        $q->with(['power','category']);
                    });
                })->paginate(100);
            }

            return view('manager.lab-request.received.delivered', compact('requests','requests_out'));
        }

        // // other products
        // $other_orders  =   Invoice::where('company_id', userInfo()->company_id)->whereNotIn('status', ['delivered', 'sent to lab', 'in production', 'completed'])->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();


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
            $product->save();


            TrackOrderRecord::create([
                'status'        =>  'production',
                'user_id'       =>  auth()->user()->id,
                'invoice_id'    =>  $sold->invoice_id,
            ]);

            $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, '1', '0', 'in production', 'wip', 'in');
        }

        return redirect()->back()->with('successMsg', 'Request sent to Production!');
    }

    // ==============
    function sendToCompleted(Request $request)
    {
        if ($request->invoiceId == null && !$request->has('invoiceId')) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        }
        if ($request->idsalfjei !=null && !$request->has('invoiceId')) {
            Invoice::where($request->idsalfjei)->update([
                'status' => 'completed',
            ]);
        }
         else {
            foreach ($request->invoiceId as $value) {
                Invoice::find($value)->update([
                    'status' => 'completed',
                ]);

                TrackOrderRecord::create([
                    'status'        =>  'completed',
                    'user_id'       =>  auth()->user()->id,
                    'invoice_id'    =>  $value,
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

                TrackOrderRecord::create([
                    'status'        =>  'delivered',
                    'user_id'       =>  auth()->user()->id,
                    'invoice_id'    =>  $value,
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

                TrackOrderRecord::create([
                    'status'        =>  'received',
                    'user_id'       =>  auth()->user()->id,
                    'invoice_id'    =>  $value,
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

                TrackOrderRecord::create([
                    'status'        =>  'collected',
                    'user_id'       =>  auth()->user()->id,
                    'invoice_id'    =>  $value,
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

        TrackOrderRecord::create([
            'status'        =>  $invoice != null ? 'Confirmed' : 'priced',
            'user_id'       =>  auth()->user()->id,
            'invoice_id'    =>  $value,
        ]);

        return redirect()->back()->with('successMsg', 'Order price set!');
    }

    function requestConfirmPayment($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'Confirmed',
            'payment_approval' => now(),
        ]);

        TrackOrderRecord::create([
            'status'        =>  'Confirmed',
            'user_id'       =>  auth()->user()->id,
            'invoice_id'    =>  Crypt::decrypt($id),
        ]);

        return redirect()->back()->with('successMsg', 'Order Payment confirmed');
    }

    function requestCancelPayment($id)
    {
        Invoice::find(Crypt::decrypt($id))->update([
            'status' => 'Canceled',
            'canceled' => now(),
        ]);

        TrackOrderRecord::create([
            'status'        =>  'Canceled',
            'user_id'       =>  auth()->user()->id,
            'invoice_id'    =>  Crypt::decrypt($id),
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

                TrackOrderRecord::create([
                    'status'        =>  'sent to supplier',
                    'user_id'       =>  auth()->user()->id,
                    'invoice_id'    =>  $value,
                ]);
            }
            return redirect()->back()->with('successMsg', 'Request Sent To Supplier!');
        }
    }

    function sendRequestTolab(Request $request){

        // dd($request->all());

        $productRepo    =   new ProductRepo();
        $lensType       =   LensType::all();
        $powers         =   Power::where('company_id',userInfo()->company_id)->get();
        $invoices       =   Invoice::where('company_id',userInfo()->company_id)->get();

        if ($request->requestId == null) {
            return redirect()->back()->with('warningMsg', 'Select at least one Order!');
        } else {
            foreach ($request->requestId as $key => $invoiceId) {
                $invoice   =   $invoices->where('id',$invoiceId)->first();
                $unavailableProductsCount   =   count($invoice->unavailableproducts);

                // dd($unavailableProductsCount);


                if ($unavailableProductsCount==2) {
                    $lenT   =   $lensType->where('id',$invoice->unavailableproducts[0]->type_id)->pluck('name')->first();
                    // dd($lenT);

                    // checking for the lens type to know how to compare them
                    if (initials($lenT)=='SV') {
                        if (
                            $invoice->unavailableproducts[0]->sphere == $invoice->unavailableproducts[1]->sphere
                            &&
                            $invoice->unavailableproducts[0]->cylinder == $invoice->unavailableproducts[1]->cylinder
                            ){
                                $f_product_id   =   $powers->where('type_id',$invoice->unavailableproducts[0]->type_id)
                                                            ->where('chromatics_id',$invoice->unavailableproducts[0]->chromatic_id)
                                                            ->where('coating_id',$invoice->unavailableproducts[0]->coating_id)
                                                            ->where('sphere',format_values($invoice->unavailableproducts[0]->sphere))
                                                            ->where('cylinder',format_values($invoice->unavailableproducts[0]->cylinder))->first();
                                // when product is found

                                if (!is_null($f_product_id)) {
                                    foreach ($invoice->unavailableproducts as $key => $unProduct) {
                                        $unProduct->update([
                                            'product_id'=>$f_product_id->product_id
                                        ]);
                                    }
                                }
                                // when product not found
                                else{
                                    $newProduct =   $productRepo->saveUnavailableToStock($invoice->unavailableproducts[0]->toArray());
                                    $prdt       =   Product::find($newProduct->id);
                                    $pstock     =   $prdt->stock;

                                    $prdt->stock = $unavailableProductsCount;
                                    $prdt->save();

                                    foreach ($invoice->unavailableproducts as $key => $unProduct) {
                                        UnavailableProduct::where('id',$unProduct->id)->update([
                                            'product_id' => $prdt->id,
                                        ]);
                                        $this->stocktrackRepo->saveTrackRecord($prdt->id, $pstock, '1', 1, 'received from supplier', 'rm', 'in');
                                    }
                                }
                        }
                        // if single vision but power not equal
                        else{
                            foreach ($invoice->unavailableproducts as $key => $unProduct) {

                                $newProduct =   $productRepo->saveUnavailableToStock($unProduct->toArray());
                                $prdt       =   Product::find($newProduct->id);
                                $pstock     =   $prdt->stock;

                                $prdt->stock = 1;
                                $prdt->save();

                                UnavailableProduct::where('id',$unProduct->id)->update([
                                    'product_id' => $prdt->id,
                                ]);
                                $this->stocktrackRepo->saveTrackRecord($prdt->id, $pstock, '1', 1, 'received from supplier', 'rm', 'in');
                            }
                        }
                    }
                    // if not single vision
                    else{
                        if ( $invoice->unavailableproducts[0]->sphere == $invoice->unavailableproducts[1]->sphere
                            &&
                            $invoice->unavailableproducts[0]->cylinder == $invoice->unavailableproducts[1]->cylinder
                            &&
                            $invoice->unavailableproducts[0]->axis == $invoice->unavailableproducts[1]->axis
                            &&
                            $invoice->unavailableproducts[0]->addition == $invoice->unavailableproducts[1]->addition
                            ){
                            $f_product_id   =   $powers->where('type_id',$invoice->unavailableproducts[0]->type_id)
                                                ->where('chromatics_id',$invoice->unavailableproducts[0]->chromatic_id)
                                                ->where('coating_id',$invoice->unavailableproducts[0]->coating_id)
                                                ->where('sphere',format_values($invoice->unavailableproducts[0]->sphere))
                                                ->where('cylinder',format_values($invoice->unavailableproducts[0]->cylinder))
                                                ->where('axis',format_values($invoice->unavailableproducts[0]->axis))
                                                ->where('add',format_values($invoice->unavailableproducts[0]->addition))
                                                ->first();

                            // when product is found
                            if (!is_null($f_product_id)) {
                                foreach ($invoice->unavailableproducts as $key => $unProduct) {
                                    $unProduct->update([
                                        'product_id'=>$f_product_id->product_id
                                    ]);
                                }
                            }
                            // when product not found
                            else{
                                $newProduct =   $productRepo->saveUnavailableToStock($invoice->unavailableproducts[0]->toArray());
                                $prdt       =   Product::find($newProduct->id);
                                $pstock     =   $prdt->stock;

                                $prdt->stock = 1;
                                $prdt->save();

                                foreach ($invoice->unavailableproducts as $key => $unProduct) {
                                    UnavailableProduct::where('id',$unProduct->id)->update([
                                        'product_id' => $prdt->id,
                                    ]);
                                    $this->stocktrackRepo->saveTrackRecord($prdt->id, $pstock, '1', 1, 'received from supplier', 'rm', 'in');
                                }
                            }
                        }
                        // if not single vision but power not equal
                        else{
                            foreach ($invoice->unavailableproducts as $key => $unProduct) {

                                $newProduct =   $productRepo->saveUnavailableToStock($unProduct->toArray());
                                $prdt       =   Product::find($newProduct->id);
                                $pstock     =   $prdt->stock;

                                $prdt->stock = 1;
                                $prdt->save();

                                UnavailableProduct::where('id',$unProduct->id)->update([
                                    'product_id' => $prdt->id,
                                ]);
                                $this->stocktrackRepo->saveTrackRecord($prdt->id, $pstock, '1', 1, 'received from supplier', 'rm', 'in');
                            }
                        }
                    }
                }

                // if only one unavailable product
                if ($unavailableProductsCount==1) {

                    // dd($unavailableProductsCount);
                    foreach ($invoice->unavailableproducts as $key => $unProduct) {

                        $newProduct =   $productRepo->saveUnavailableToStock($unProduct->toArray());
                        $prdt       =   Product::find($newProduct->id);
                        $pstock     =   $prdt->stock;

                        $prdt->stock = 1;
                        $prdt->save();

                        UnavailableProduct::where('id',$unProduct->id)->update([
                            'product_id' => $prdt->id,
                        ]);
                        $this->stocktrackRepo->saveTrackRecord($prdt->id, $pstock, '1', 1, 'received from supplier', 'rm', 'in');
                    }
                }
                if ($unavailableProductsCount==0){
                    foreach ($invoice->SoldProduct as $key => $pr) {

                        $prdt       =   Product::find($pr->product_id);

                        if (!is_null($prdt) && $prdt->category_id=='1') {
                            $prdt       =   Product::find($pr->product_id);
                            $prdt->stock += 1;
                            $prdt->save();
                            $this->stocktrackRepo->saveTrackRecord($prdt->id, $prdt->stock, '1', 1, 'received from supplier', 'rm', 'in');
                        }
                    }

                }
                $this->sendToLab(Crypt::encrypt($invoiceId));
            }
            return redirect()->back()->with('successMsg', 'Request sent to Lab!');
        }
    }
}
