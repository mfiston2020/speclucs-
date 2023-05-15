<?php

namespace App\Http\Controllers\Seller;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $is_july    =   'yes';

        $sales  =   \App\Models\Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','ASC')->get();
        return view('seller.sales.index',compact('sales','is_july'));
    }

    public function addCustomerSale()
    {
        $customers  =  \App\Models\Customer::where('company_id',Auth::user()->company_id)->get();
        return view('seller.sales.customerForm',compact('customers'));
    }

    public function create()
    {
        $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id',Auth::user()->company_id)->get());
        $pending    =   count(DB::table('invoices')->select('*')->where('status','=','pending')->where('company_id',Auth::user()->company_id)->where('user_id','=',Auth::user()->id)->get());

        if ($pending>0) 
        {
            return redirect()->back()->with('warningMsg','Complete pending invoices first!');
        }
        else{
            $invoice    =   new \App\Models\Invoice();

            $invoice->reference_number  =   $reference+1;
            $invoice->status            =   'pending';
            $invoice->user_id           =   Auth::user()->id;
            $invoice->total_amount      =   '0';
            $invoice->company_id        =   Auth::user()->company_id;

            try {
                $invoice->save();
                return redirect()->route('seller.sales.edit',Crypt::encrypt($invoice->id));
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        }
    }

    public function createCustomerInvoice(Request $request)
    {
        $this->validate($request,[
            'customer'=>'required',
        ]);

        $customer_found  =   0;
        $invoice_id     =   0;


        $invoices  =   DB::table('invoices')->select('*')->where('company_id',Auth::user()->company_id)->get();

        foreach ($invoices as $key => $invoice) 
        {
            if ($invoice->client_id ==  $request->customer && $invoice->status=='pending') 
            {
                $customer_found  =   1;
                $invoice_id     =   $invoice->id;
            }
        }

        if ($customer_found==1) 
        {
            return redirect()->route('seller.sales.edit',Crypt::encrypt($invoice_id));
        } 
        else 
        {
            $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id',Auth::user()->company_id)->get());
            $pending    =   count(DB::table('invoices')->select('*')->where('company_id',Auth::user()->company_id)->where('status','=','pending')->where('user_id','=',Auth::user()->id)->get());
            
            $invoice    =   new \App\Models\Invoice();

            $invoice->reference_number  =   $request->reference_number;
            $invoice->status            =   'pending';
            $invoice->user_id           =   Auth::user()->id;
            $invoice->client_id         =   $request->customer;
            $invoice->total_amount      =   '0';
            $invoice->company_id        =   Auth::user()->company_id;

            try {
                $invoice->save();
                return redirect()->route('seller.sales.edit',Crypt::encrypt($invoice->id));
            } 
            catch (\Throwable $th) 
            {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        }
    }

    public function edit($id)
    {
        $id =   Crypt::decrypt($id);
        
        $invoice    =   \App\Models\Invoice::find($id);
        $products   =   DB::table('sold_products')->select('*')->where('invoice_id','=',$id)->get();

        return view('seller.sales.detail',compact('invoice','products'));
    }

    public function addSalesProduct($id)
    {
        $id =   Crypt::decrypt($id);
        
        $products   =   \App\Models\Product::orderBy('product_name','DESC')->where('company_id',Auth::user()->company_id)->where('category_id','<>','1')->get();

        $categories =   \App\Models\Category::all();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index   =   \App\Models\PhotoIndex::all();

        return view('seller.sales.addProduct',compact('products','id','categories','lens_types','chromatics','coatings','index'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'product'=>'required',
            'unit_price'=>'required | integer',
            'quantity'=>'required | integer',
            'total_amount'=>'required | integer',
        ]);

        $_product   =   \App\Models\SoldProduct::where('company_id',Auth::user()->company_id)->get();
        $existing_product   =  0;

        foreach ($_product as $key => $item) {
            if ($request->product==$item->product_id && $request->invoice_id==$item->invoice_id) 
            {
                $existing_product   =   1;
                $id                 =   $item->id;
            }
        }

        if ($existing_product==0) 
        {

            $sold   =   new \App\Models\SoldProduct();

            $sold->invoice_id   =   $request->invoice_id;
            $sold->product_id   =   $request->product;
            $sold->quantity     =   $request->quantity;
            $sold->unit_price   =   $request->unit_price;
            $sold->total_amount =   $request->total_amount;
            $sold->company_id   =   Auth::user()->company_id;

            try {
                $sold->save();
                return redirect()->route('seller.sales.edit',Crypt::encrypt($request->invoice_id))->with('successMsg','The product has been disposed of successfully.! ');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
            }
        }
        else{
            $product = \App\Models\SoldProduct::find($id);

            $product->quantity        =   $request->quantity + $product->quantity;
            
            try {
                $product->save();
                return redirect()->route('seller.sales.edit',Crypt::encrypt($request->invoice_id))->with('successMsg','Product has been successfully Added!');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
            }
        }
    }

    public function fetchProduct(Request $request)
    {
        $product    =   \App\Models\Product::find($request->id);
        return response()->json($product);
    }

    public function fetchProductData(Request $request)
    {
        $type           =   \App\Models\LensType::find($request->lens_types);
        if (initials($type->name)=='SV') 
        {
            $product_id     =   \App\Models\Power::where('type_id',$type->id)
                            ->where('index_id',$request->index)
                            ->where('chromatics_id',$request->chromatics)
                            ->where('coating_id',$request->coating)
                            ->where('sphere',format_values($request->sphere))
                            ->where('cylinder',format_values($request->cylinder))
                            // ->where('axis',format_values($request->axis))
                            // ->where('add',format_values($request->add))
                            ->where('eye','any')
                            ->where('company_id',Auth::user()->company_id)
                            ->select('product_id')->first();

        $product    =   \App\Models\Product::find($product_id);
        // $product    =   $request->all();
        }
        else
        {
            $product_id     =   \App\Models\Power::where('type_id',$type->id)
                            ->where('index_id',$request->index)
                            ->where('chromatics_id',$request->chromatics)
                            ->where('coating_id',$request->coating)
                            ->where('sphere',format_values($request->sphere))
                            ->where('cylinder',format_values($request->cylinder))
                            ->where('axis',format_values($request->axis))
                            ->where('add',format_values($request->add))
                            ->where('eye',$request->eye)
                            ->where('company_id',Auth::user()->company_id)
                            ->select('product_id')->first();

        $product    =   \App\Models\Product::find($product_id);
        // $product    =   $request->all();
        }
        
        return response()->json($product);
    }

    public function finalize(Request $request,$id)
    {
        $id =   Crypt::decrypt($id);

        $quantity   =   0;
        $allProducts    =   \App\Models\SoldProduct::where(['invoice_id'=>$id])->where('company_id',Auth::user()->company_id)->select('*')->get();
        
        if ($allProducts->isEmpty()) {
            return redirect()->back()->withInput()->with('errorMsg','Please add products first! ');
        } else {
            // getting all  the products of this invoice
        foreach($allProducts as $product)
        {
            $p[]    =   $product->product_id;
        }

        $pro    =   array_unique($p);

        // comparing quantities
        foreach($pro as $product){
            $product_   =   \App\Models\SoldProduct::where(['product_id'=>$product])->where('company_id',Auth::user()->company_id)->where(['invoice_id'=>$id])->select('*')->sum('quantity');

            $product_stock  =   \App\Models\Product::find($product);

            if($product_>$product_stock->stock)
            {
                return redirect()->back()->with('warningMsg','The product \''.$product_stock->product_name.'\' does not have enough stock. Only has '.$product_stock->stock.' units.');
            }
            else{
                $product_stock->stock   =   $product_stock->stock-$product_;
                $product_stock->save();
            }
            $quantity   =   0;
        }
        // ==============

        $sold_product   =   \App\Models\Invoice::find($id);

        $sold_product->status       =   'completed';
        $sold_product->total_amount =   $request->total;
        $sold_product->client_name  =   $request->name;
        $sold_product->phone        =   $request->phone;
        $sold_product->tin_number   =   $request->tin_number;

        try {
            $sold_product->save();
            return redirect()->back()->with('successMsg','Sale has been Finalized successfully! ');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
        }
        
    }

    public function removeSaleProduct($id)
    {
        $product    =   \App\Models\SoldProduct::find(Crypt::decrypt($id));
        try {
            $product->delete();
            return redirect()->back()->with('successMsg','Product successfully removed');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }

    public function editSaleProduct($id)
    {
        $product    =   \App\Models\SoldProduct::where(['id'=>Crypt::decrypt($id)])->where('company_id',Auth::user()->company_id)->select("*")->first();

        return view('seller.sales.editProduct',compact('product'));
    }

    public function updateSaleProduct(Request $request,$id)
    {
        $this->validate($request,[
            'unit_price'=>'required | integer',
            'quantity'=>'required | integer',
            'total_amount'=>'required | integer',
        ]);

        $sold   =   \App\Models\SoldProduct::find(Crypt::decrypt($id));

        $sold->quantity     =   $request->quantity;
        $sold->total_amount =   $request->total_amount;

        try {
            $sold->save();
            return redirect()->route('seller.sales.edit',Crypt::encrypt($request->invoice_id))->with('successMsg','The product has been updated of successfully.! ');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    public function payInvoice ($id)
    {
        $product    =   \App\Models\Invoice::where(['id'=>Crypt::decrypt($id)])->where('company_id',Auth::user()->company_id)->select("*")->first();
        $payment_method =   \App\Models\PaymentMethod::all();

        return view('seller.sales.payment',compact('product','payment_method'));
    }

    public  function invoiceTransaction(Request $request,$id)
    {
        $this->validate($request,[
            'payment_type'=>'required',
            'payment'=>'required',
            'amount_paid'=>'required | integer',
            'remain'=>'required | integer',
        ]);

        $invoice    =   \App\Models\Invoice::find(Crypt::decrypt($id));

        $invoice->payment   =   'paid';
        $invoice->due       =   $request->remain;
        
        try {
            $invoice->save();

            $transaction    =   new \App\Models\Transactions();

            $transaction->invoice_id            =   $invoice->id;
            $transaction->user_id               =   Auth::user()->id;
            $transaction->payment_method_id     =   $request->payment;
            $transaction->type                  =   'income';

            $transaction->amount                =   $request->amount_paid;
            $transaction->title                 =   'payment from customer';
            $transaction->company_id            =   Auth::user()->company_id;

            try {
                $transaction->save();
                return redirect()->route('seller.sales.edit',Crypt::encrypt($invoice->id))->with('successMsg','The Invoice has been successfully Paid!');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    public function invoicedelete($id){
        $invoice    =   \App\Models\Invoice::find(Crypt::decrypt($id));
        try {
            $invoice->delete();
            return redirect()->back()->with('successMsg','The Invoice has been successfully Deleted!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    public function edit_sales_data(Request $request)
    {

        $hello  =array();
        // return $request->all();

        foreach ($request->order_id as $key => $id) 
        {
            $invoice    =   \App\Models\Invoice::find($id);

            $invoice->created_at    =   date('y-m-d',strtotime($request->order_date[$key]));
            $invoice->reference_number  =   $request->reference_number[$key];
            
            try {
                $invoice->save();
            } catch (\Throwable $th) {
                
                return redirect()->route('seller.sales')->with('errorMsg','Something went wrong!');
            }
        }
    return redirect()->route('seller.sales')->with('successMsg','Order Edited!');
    }
}
