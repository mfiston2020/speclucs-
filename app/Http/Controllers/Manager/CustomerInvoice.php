<?php

namespace App\Http\Controllers\Manager;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\SupplyRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CustomerInvoice extends Controller
{
    public function index()
    {
        $customers  =   \App\Models\Customer::where('company_id',Auth::user()->company_id)
                        ->select('name','id')->get();
        $visionCenters  =   SupplyRequest::where('supplier_id',userInfo()->company_id)->where('status','approved')->select('request_from')->with('requestCompany:id,company_name')->get();

        return view('manager.customerInvoice.index', compact('customers','visionCenters'));
    }

    // ========================================
    public function search(Request $request)
    {
        $this->validate($request,[
            'customer'=>'required',
        ]);

        // $supplier

        if (!$request->from==null){
            $client_id  =   $request->customer;

            $customerInvoice    =   Invoice::where('client_id',$request->customer)
                                            ->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('invoiceState','=',NULL)
                                            ->where('payment','=',NULL)
                                                ->orderBy('created_at','desc')
                                            ->select('*')
                                            ->get();


            if ($customerInvoice->isEmpty())
            {
                $from   =   $request->from;
                $to     =   $request->to;


                $customerInvoice    =   Invoice::where('supplier_id',Auth::user()->company_id)
                                                ->where('invoiceState','=',NULL)
                                                ->with('orderrecord',function($query) use ($from,$to){
                                                    $query->where('status','delivered')
                                                            ->whereDate('created_at','>=',date('Y-m-d',strtotime($from)))
                                                            ->whereDate('created_at','<=',date('Y-m-d',strtotime($to.'+1day')));
                                                })
                                                ->whereIn('status',['received','delivered','collected'])
                                                ->where('payment','=',NULL)
                                                ->orderBy('created_at','desc')
                                                ->select('*')
                                                ->get();


                if ($customerInvoice->isEmpty()) {
                    return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
                }else{
                    if ($customerInvoice[0]->orderrecord->isEmpty()) {
                        return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
                    }
                    $from   =   $request->from;
                    $to   =   $request->to;

                    // return $customerInvoice;
                    return view('manager.customerInvoice.result',compact('customerInvoice','from','to','client_id'));
                }
            }
            else
            {
                $from   =   $request->from;
                $to   =   $request->to;

                // return $customerInvoice;
                return view('manager.customerInvoice.result',compact('customerInvoice','from','to','client_id'));
            }
        }

        if ($request->from==null && $request->to==null){
            $client_id  =   $request->customer;

            $customerInvoice    =   \App\Models\Invoice::where('client_id',$request->customer)
                                    // ->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                                    // ->orWhereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                                    ->where('company_id',Auth::user()->company_id)
                                    ->where('status','=','completed')
                                    // ->where('invoiceState','=',NULL)
                                    ->where('payment','=',NULL)
                                    ->select('*')->get();


            if ($customerInvoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                $from   =   $request->from;
                $to   =   $request->to;

                // return $customerInvoice;
                return view('manager.customerInvoice.result',compact('customerInvoice','from','to','client_id'));
            }
        }

        if (!$request->to==null){
            $client_id  =   $request->customer;

            $customerInvoice    =   \App\Models\Invoice::where('client_id',$request->customer)
                                    // ->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                                    ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                                    ->where('status','=','completed')
                                    // ->where('invoiceState','=',NULL)
                                    ->where('payment','=',NULL)
                                    ->select('*')->get();


            if ($customerInvoice->isEmpty()){
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                $from   =   $request->from;
                $to   =   $request->to;

                // return $customerInvoice;
                return view('manager.customerInvoice.result',compact('customerInvoice','from','to','client_id'));
            }
        }
        else
        {
            $client_id  =   $request->customer;

            $customerInvoice    =   \App\Models\Invoice::where('client_id',$request->customer)->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                                    ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                                    ->where('status','=','completed')
                                    // ->where('invoiceState','=',NULL)
                                    ->where('payment','=',NULL)->select('*')->get();


            if ($customerInvoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            } else {

                // return $customerInvoice;
                $from   =   $request->from;
                $to   =   $request->to;
                return view('manager.customerInvoice.result',compact('customerInvoice','from','to','client_id'));
            }
        }
    }

    // =============================================================================================
    public function statementInvoiceCreate(Request $request)
    {
        $reference_number   =   count(\App\Models\InvoiceStatement::where('Company_id',Auth::user()->company_id)->select('*')->get());
        $total_invoice_amount   =   0;

        if (!$request->invoice)
        {
            return redirect()->back()->with('warningMsg','Please select at least one invoice!');
        }
        else
        {
            // dd($request->all())
            $hassupplier=false;

            for ($i=0; $i < count($request->invoice); $i++)
            {
                $invoice    =   \App\Models\Invoice::findOrFail($request->invoice[$i]);
                $total_invoice_amount   =   $total_invoice_amount + $invoice->sumOfCategorizedproduct()['total'];

                if (!is_null($invoice->supplier_id)) {
                    $hassupplier=true;
                }
            }
            // dd($total_invoice_amount);

            $statement_invoice  =   new \App\Models\InvoiceStatement();

            $statement_invoice->invoice_number  =   $reference_number+1;
            $statement_invoice->customer_id     =   $request->client_id;
            $statement_invoice->company_id      =   Auth::user()->company_id;
            $statement_invoice->all_invoice     =   count($request->invoice);
            $statement_invoice->toDate          =   $total_invoice_amount;
            $statement_invoice->fromDate        =   $total_invoice_amount;
            $statement_invoice->total_amount    =   $total_invoice_amount;
            $statement_invoice->status          =   'invoiced';
            $statement_invoice->source          =   $hassupplier?'vision center':'customer';

            try {
                $statement_invoice->save();

                for ($i=0; $i < count($request->invoice); $i++){
                    $invoice    =   \App\Models\Invoice::findOrFail($request->invoice[$i]);

                    $st_invoice                 =   new \App\Models\Manager\StatementInvoice();
                    $st_invoice->company_id     =   Auth::user()->company_id;
                    $st_invoice->statement_id   =   $statement_invoice->id;
                    $st_invoice->invoice_id     =   $request->invoice[$i];
                    $st_invoice->total_amount   =   $invoice->sumOfCategorizedproduct()['total'];
                    $st_invoice->save();

                    $invoice->statement_id  =   $statement_invoice->id;
                    $invoice->payment       =   'invoiced';
                    $invoice->save();
                }
            return redirect()->route('manager.cutomerInvoice')->with('successMsg','Invoices Successfully Saved!');
            }
            catch (\Throwable $th)
            {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
            }
        }
    }

    public function statementInvoiceSearch(Request $request)
    {
        if ($request->customer_name=='any')
        {
            $invoice_id_array          =   array();
            $invoice_orders_id         =   array();
            $invoice_products          =   array();

            $client_id              =   $request->customer_name;

            $invoice    =   \App\Models\InvoiceStatement::whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                            ->orwhere('status',$request->status)
                            ->orwhere('invoice_number',$request->statement_number)
                            ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                            ->where('company_id',Auth::user()->company_id)
                            ->select('id')
                            ->get();

            if ($invoice->isEmpty())
            {
                $invoice    =   \App\Models\InvoiceStatement::whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                            ->orwhere('status',$request->status)
                            ->orwhere('invoice_number',$request->statement_number)
                            ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                            ->where('company_id',Auth::user()->company_id)
                            ->select('id')
                            ->get();

                if ($invoice->isEmpty()) {
                    return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
                }
            }
            else
            {
                //finding the invoice products
                foreach ($invoice as $key => $ids)
                {
                    $get_ids   =   \App\Models\Invoice::where('statement_id',$ids->id)->select('id')->get();
                    foreach ($get_ids as $key => $value)
                    {
                        array_push($invoice_orders_id,$value);
                    }
                }
                // return array_unique($invoice_orders_id);

                foreach ($invoice_orders_id as $key => $value)
                {
                    $products   =   \App\Models\SoldProduct::where('invoice_id',$value->id)->select('*')->get();
                    foreach ($products as $key => $product) {
                        $invoice_products[]   =   $product;
                    }
                }

                // return $invoice_products;
                return view('manager.customerInvoice.orderDetail',compact('invoice_products','client_id'));
            }
        }
        elseif ($request->customer_name==null && $request->from==null && $request->to==null &&  $request->statement_number==null)
        {
            $invoice_id_array          =   array();
            $invoice_orders_id         =   array();
            $invoice_products          =   array();

            $client_id              =   $request->customer_name;

            $invoice    =   \App\Models\InvoiceStatement::select('id')->where('company_id',Auth::user()->company_id)
                            ->get();

            if ($invoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                //finding the invoice products
                foreach ($invoice as $key => $ids)
                {
                    $invoice_orders   =   \App\Models\Invoice::where('statement_id',$ids->id)->select('id')->get();
                    foreach ($invoice_orders as $key => $value) {
                        array_push($invoice_orders_id,$value);
                    }
                }
                // return $invoice_orders_id;

                foreach ($invoice_orders_id as $key => $value)
                {
                    $products   =   \App\Models\SoldProduct::where('invoice_id',$value->id)->select('*')->get();
                    foreach ($products as $key => $product) {
                        $invoice_products[]   =   $product;
                    }
                }

                // return $invoice_products;
                return view('manager.customerInvoice.orderDetail',compact('invoice_products','client_id'));
            }
        }
        elseif ($request->statement_number)
        {
            $invoice_id_array          =   array();
            $invoice_orders_id         =   array();
            $invoice_products          =   array();

            $client_id              =   $request->customer_name;

            $invoice    =   \App\Models\InvoiceStatement::where('invoice_number',(int)$request->statement_number)
                            // ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                            ->where('company_id',Auth::user()->company_id)
                            ->select('id')
                            ->get();


            if ($invoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                //finding the invoice products
                foreach ($invoice as $key => $ids)
                {
                    $get_ids   =   \App\Models\Invoice::where('statement_id',$ids->id)->select('id')->get();
                    foreach ($get_ids as $key => $value)
                    {
                        array_push($invoice_orders_id,$value);
                    }
                }
                // return array_unique($invoice_orders_id);

                foreach ($invoice_orders_id as $key => $value)
                {
                    $products   =   \App\Models\SoldProduct::where('invoice_id',$value->id)->select('*')->get();
                    foreach ($products as $key => $product) {
                        $invoice_products[]   =   $product;
                    }
                }

                // return $invoice_products;
                return view('manager.customerInvoice.orderDetail',compact('invoice_products','client_id'));
            }
        }
        elseif (!$request->from==null || !$request->to==null)
        {
            $invoice_id_array          =   array();
            $invoice_orders_id         =   array();
            $invoice_products          =   array();

            $client_id              =   $request->customer_name;

            $invoice    =   \App\Models\InvoiceStatement::whereDate('created_at','>=',date('Y-m-d',strtotime($request->from)))
                        ->orwhere('status',$request->status)
                        ->orwhere('invoice_number',$request->statement_number)
                        ->where('company_id',Auth::user()->company_id)
                        // ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                        ->select('id')
                        ->get();


            if ($invoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                //finding the invoice products
                foreach ($invoice as $key => $ids)
                {
                    $get_ids   =   \App\Models\Invoice::where('statement_id',$ids->id)->select('id')->get();
                    foreach ($get_ids as $key => $value)
                    {
                        array_push($invoice_orders_id,$value);
                    }
                }
                // return array_unique($invoice_orders_id);

                foreach ($invoice_orders_id as $key => $value)
                {
                    $products   =   \App\Models\SoldProduct::where('invoice_id',$value->id)->select('*')->get();
                    foreach ($products as $key => $product) {
                        $invoice_products[]   =   $product;
                    }
                }

                // return $invoice_products;
                return view('manager.customerInvoice.orderDetail',compact('invoice_products','client_id'));
            }
        }
        else
        {
            $invoice_id_array          =   array();
            $invoice_orders_id         =   array();
            $invoice_products          =   array();

            $client_id              =   $request->customer_name;

            $invoice    =   \App\Models\InvoiceStatement::where('customer_id',$request->customer_name)
                            ->orwhere('status',$request->status)
                            ->orwhere('invoice_number',$request->statement_number)
                            ->whereDate('created_at','>=',strtotime($request->from))
                            ->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to)))
                            ->where('company_id',Auth::user()->company_id)
                            ->select('id')
                            ->get();

            if ($invoice->isEmpty())
            {
                return redirect()->back()->with('warningMsg','No invoice found for this customer')->withInput();
            }
            else
            {
                //finding the invoice products
                foreach ($invoice as $key => $ids)
                {
                    $invoice_orders   =   \App\Models\Invoice::where('statement_id',$ids->id)->select('id')->get();
                    foreach ($invoice_orders as $key => $value) {
                        array_push($invoice_orders_id,$value);
                    }
                }
                // return $invoice_orders_id;

                foreach ($invoice_orders_id as $key => $value)
                {
                    $products   =   \App\Models\SoldProduct::where('invoice_id',$value->id)->select('*')->get();
                    foreach ($products as $key => $product) {
                        $invoice_products[]   =   $product;
                    }
                }

                // return $invoice_products;
                return view('manager.customerInvoice.orderDetail',compact('invoice_products','client_id'));
            }
        }
    }

    public function summary(Request $request)
    {
        // return $request->all();

        $this->validate($request,[
            'customer'=>'required',
        ]);

        $from   =   date('Y-m-d',strtotime($request->from));
        $to     =   date('Y-m-d',strtotime($request->to));
        $sold_products=null;

        // return $from;

        $invoices   =   \App\Models\InvoiceStatement::where('customer_id',$request->customer)
                        // ->whereBetween('created_at',[$from, $to])
                        ->whereDate('created_at', '>=', date('Y-m-d', strtotime($from)))
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($to . '+1day')))
                        ->where('company_id',Auth::user()->company_id)
                        ->get();

        if($invoices->isEmpty())
        {
            return redirect()->route('manager.cutomerInvoice')->with('warningMsg','There are no invoices at the moment!');
        }

        $customer   =   \App\Models\Customer::findOrFail($request->customer);
        $customer2   =   \App\Models\CompanyInformation::findOrFail($request->customer);

        foreach ($invoices as $key => $invoice) {
            $invoice_numbers[]    =   \App\Models\Invoice::where('statement_id',$invoice->id)->where('company_id',Auth::user()->company_id)->select('*')->first();
        }

        // return $invoice_numbers;

        foreach ($invoice_numbers as $key => $value)
        {
            if($value!=null){

                $sold_products[]  =   \App\Models\SoldProduct::join('invoices','sold_products.invoice_id','invoices.id')
                                    ->join('invoice_statements','invoices.statement_id','invoice_statements.id')
                                    ->select('sold_products.*','invoices.reference_number as number','invoices.id as invoice_id',
                                    'invoice_statements.id as statement_id','invoice_statements.status as status')
                                    ->where('invoice_id',$value->id)
                                    ->first();
            }
        }

        return view('manager.customerInvoice.summary',compact('invoices','sold_products','customer','customer2'));
    }

    public function detail($id)
    {
        $invoice_orders_id  =   array();
        $invoice_products   =   array();
        $products_temp   =   array();
        $final_product_list =   array();
        $another_array      =   array();

        $invoice =   \App\Models\InvoiceStatement::findOrFail(Crypt::decrypt($id));

        $get_ids   =   \App\Models\Invoice::where('statement_id',$invoice->id)->select('id')->get();

        foreach ($get_ids as $key => $value)
        {
            array_push($invoice_orders_id,$value->id);
        }

        foreach ($invoice_orders_id as $key => $value)
        {
            $p   =   \App\Models\SoldProduct::where('invoice_id',$value)->select('*')->get();

            foreach ($p as $key => $pro)
            {
                $product_full   =   DB::table('products')->join('sold_products','products.id','sold_products.product_id')
                                    ->select('products.*','sold_products.*')->where('sold_products.id',$pro->id)
                                    ->where('company_id',Auth::user()->company_id)
                                    ->first();

                array_push($products_temp,$product_full);
            }
        }

        foreach ($products_temp as $key => $product_)
        {
            $sum =  0;
            if (empty($final_product_list))
            {
                $final_product_list=array(
                    'product_id'=>$product_->product_id,
                    'name'=>$product_->product_name,
                    'description'=>$product_->description,
                    'unit_price'=>$product_->unit_price,
                    'quantity'=>$product_->quantity,
                );

                array_push($invoice_products,$final_product_list);
            }
            else
            {
                for ($i=0; $i < count($final_product_list); $i++)
                {
                    if ($final_product_list['description']==$product_->description && $final_product_list['unit_price']==$product_->unit_price)
                    {
                        for ($j=0; $j < count($invoice_products); $j++)
                        {
                            if ($final_product_list['description']==$invoice_products[$j]['description'] && $final_product_list['unit_price']==$product_->unit_price) {
                                $another_array[$j]=array(
                                    'product_id'=>$product_->product_id,
                                    'name'=>$product_->product_name,
                                    'description'=>$product_->description,
                                    'unit_price'=>$product_->unit_price,
                                    'quantity'=>$product_->quantity + $invoice_products[$j]['quantity'],
                                );
                            }
                        }
                    }
                    else
                    {
                        $final_product_list=array(
                            'product_id'=>$product_->product_id,
                            'name'=>$product_->product_name,
                            'description'=>$product_->description,
                            'unit_price'=>$product_->unit_price,
                            'quantity'=>$sum,
                        );

                        array_push($invoice_products,$final_product_list);
                    }
                }
            }
        }
        return $another_array;

        return view('manager.customerInvoice.receipt',compact('invoice_products','invoice'));

    }

    public function statementInvoicePay(Request $request)
    {
        if (!$request->invoice)
        {
            return redirect()->back()->with('warningMsg','Please select at least one invoice!');
        }
        else
        {
            $hello  =   array();
            // return $request->all();

            for ($i=0; $i < count($request->invoice); $i++){
                $statement_invoice  =   \App\Models\InvoiceStatement::findOrFail($request->invoice[$i]);
                $statement_invoice->status  =   'paid';
                $statement_invoice->save();


                $invoice    =   \App\Models\Invoice::where('statement_id',$request->invoice[$i])->select('*')->get();
                foreach ($invoice as $key => $value)
                {
                    array_push($hello,$value);
                }
            }
            foreach ($hello as $key => $prod){
                $invoice    =   \App\Models\Invoice::where('id',$prod->id)->select('*')->first();
                $invoice->payment   =   'paid';

                $invoice->save();
            }

            return redirect()->back()->with('successMsg','Invoices Successfully paid!');
        }
    }

    public function statementInvoice_detail($id)
    {
        $invoice_customer   =   \App\Models\InvoiceStatement::findOrFail(Crypt::decrypt($id));
        $all_products   =   \App\Models\Invoice::join('sold_products','sold_products.invoice_id','invoices.id')
                                        ->join('products','sold_products.product_id','products.id')
                                        ->leftJoin('powers','powers.product_id','products.id')
                                        ->where('invoices.statement_id',Crypt::decrypt($id))
                                        ->select('powers.*','invoices.*','sold_products.*','sold_products.created_at as order_date','sold_products.id as order_number'
                                                    ,'products.description')
                                        ->get();

        $customer   =   \App\Models\Customer::findOrFail($invoice_customer->customer_id);
        $customer2   =   \App\Models\CompanyInformation::findOrFail($invoice_customer->customer_id);

        // return $all_products;

        return view('manager.customerInvoice.statement',compact('invoice_customer','all_products','customer','customer2'));
    }

    // =============================================================================================
    // public function pay(Request $request)
    // // {

    //     for ($i=0; $i < count($prod); $i++)
    //     {
    //         $sum    =   0;
    //         for ($j=0; $j < count($prod); $j++)
    //         {
    //             if ($prod[$i]['description']==$prod[$j]['description'] && $prod[$j]['unit_price']==$prod[$j]['unit_price'])
    //             {
    //                 if (empty($products_temp))
    //                 {
    //                     $products_temp=array(
    //                         'name'=>$prod[$i]['name'],
    //                         'description'=>$prod[$i]['description'],
    //                         'unit_price'=>$prod[$i]['unit_price'],
    //                         'quantity'=>$prod[$i]['quantity'],
    //                     );
    //                     array_push($final_product_list,$products_temp);
    //                 }
    //                 else
    //                 {
    //                     for ($k=0; $k < count($products_temp); $k++)
    //                     {
    //                         // return $products_temp;
    //                         if ($prod[$i]['description']!=$products_temp['description'] && $prod[$j]['unit_price']!=$products_temp['unit_price'])
    //                         {
    //                             $sum    =   $sum + $prod[$i]['quantity'];
    //                             // $products_temp['quantity']=$sum;
    //                             // return $sum;
    //                         }
    //                         else
    //                         {
    //                             $products_temp=array(
    //                                 'name'=>$prod[$i]['name'],
    //                                 'description'=>$prod[$i]['description'],
    //                                 'unit_price'=>$prod[$i]['unit_price'],
    //                                 'quantity'=>$prod[$i]['quantity'],
    //                             );
    //                             array_push($final_product_list,$products_temp);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if (!$request->invoice)
    //     {
    //         return redirect()->back()->with('warningMsg','Please select at least one invoice!');
    //     }
    //     else
    //     {
    //         for ($i=0; $i < count($request->invoice); $i++)
    //         {
    //             $invoice    =   \App\Models\Invoice::findOrFail($request->invoice[$i]);
    //             $invoice->payment   =   'paid';
    //             $invoice->save();
    //         }

    //         return redirect()->back()->with('successMsg','Invoices Successfully paid!');
    //     }
    // }

    // public function email(Request $request)
    // {
    //     if (!$request->invoices)
    //     {
    //         return redirect()->back()->with('warningMsg','Please select at least one invoice!');
    //     }
    //     else
    //     {
    //         $invoice_id =   explode(",",$request->invoices);

    //         // return $invoice_id;
    //         $query  =   [];

    //         foreach ($invoice_id as $key => $invoice)
    //         {
    //             $client_id  =   \App\Models\Invoice::where('id',$invoice)->pluck('client_id')->first();

    //             $invoice_update =   \App\Models\Invoice::findOrFail($invoice);
    //             $invoice_update->emailState =   'submited';
    //             // $invoice_update->save();

    //             $query[$key]  =   \App\Models\Invoice::where('client_id','=',$client_id)->where('company_id','=',Auth::user()->company_id)
    //                         ->where('status','=','completed')
    //                         ->where('id',$invoice)
    //                         ->where('payment','=',NULL)->select('*')
    //                         ->first();
    //         }

    //     }
    //         $client=\App\Models\Customer::where('id',$client_id)->select('email','name','company_id','phone')->first();

    //         $company=\App\Models\CompanyInformation::where('id',Auth::user()->company_id)->select('*')->first();

    //         $customerInvoice    =   $query;

    //         $tot    =   0;
    //         $tot    =   0;

    //         foreach ($customerInvoice as $item)
    //         {
    //             $tot=$tot+$item->total_amount;
    //         }

    //         // ================== creating invoice statement ==============
    //         $invoic_statement   =   new \App\Models\InvoiceStatement();

    //         $invoic_statement->customer_id  =   $client;
    //         $invoic_statement->invoice_number   =   '0';
    //         $invoic_statement->all_invoice      =   count($customerInvoice);
    //         $invoic_statement->total_amount     =   $tot;
    //         $invoic_statement->status           =   'pending';
    //         try {
    //             $invoic_statement->save();

    //             foreach ($customerInvoice as $item)
    //             {
    //                 $statement_invoice  =   new \App\Models\Manager\StatementInvoice();

    //                 $statement_invoice->statement_id    =   $invoic_statement->id;
    //                 $statement_invoice->invoice_id      =   $item->id;
    //                 $statement_invoice->total_amount    =   $item->total_amount;
    //                 $statement_invoice->save();
    //             }

    //             try
    //             {
    //                 // \Mail::to('mfiston2020@gmail.com')->send(new CustomerInvoiceMail($customerInvoice,$client,$company,$tot));

    //                 return redirect()->back()->with('successMsg','Invoice was successfully sent to customer');
    //             }
    //             catch (Swift_TransportException $STe)
    //             {
    //                     return redirect()->back()->with('errorMsg','Check Your Internet Connection');
    //             }
    //         } catch (\Throwable $th) {
    //             return redirect()->back()->with('errorMsg','Something Went Wrong! ');
    //         }
    //         // $this->sendInvoice($client_id,$query);
    // }
}
