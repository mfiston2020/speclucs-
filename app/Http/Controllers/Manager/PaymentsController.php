<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments   =   \App\Models\Payment::all();
        return view('manager.payments.index',compact('payments'));
    }

    public function add()
    {
        $receipts   =   \App\Models\Receipt::where(['status'=>'completed'])->where('company_id',Auth::user()->company_id)->select('*')->get();
        $payment_methods   =   \App\Models\PaymentMethod::all();
        return view('manager.payments.add',compact('receipts','payment_methods'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'receipt'=>'required',
            'supplier'=>'required',
            'payment_method'=>'required',
            'balance'=>'required | integer',
            'amount'=>'required | integer',
            'due'=>'required | integer',
        ]);

        $payments   =   new \App\Models\Payment();
        $receipt    =   \App\Models\Receipt::find($request->receipt);

        $payments->user_id  =   Auth::user()->id;
        $payments->receipt_id  =    $request->receipt;
        $payments->payment_method_id  = $request->payment_method;
        $payments->supplier_id  =   $request->supplier_id;
        $payments->amount  =    $request->amount;
        $payments->due  =   $request->due;
        $payments->company_id=  Auth::user()->company_id;


        try {
            $payments->save();
            try {
                $transaction                    =   new \App\Models\Transactions();
                $transaction->user_id           =   Auth::user()->id;
                $transaction->payment_method_id =   $request->payment_method;
                $transaction->supplier_id       =   $request->supplier_id;
                $transaction->type              =   'expense';
                $transaction->amount            =   '-'.$request->amount;
                $transaction->title             =   $receipt->title;
                $transaction->company_id           =  Auth::user()->company_id;

                $transaction->save();
                $receipt->status    =   'paid';
                $receipt->save();

                return redirect()->route('manager.payment')->with('successMsg','Receipt Successfully Paid');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function fetchReceipt(Request $request)
    {
        $data   =   DB::table('receipts')->join('suppliers','receipts.supplier_id','=','suppliers.id')
                                         ->select('receipts.*','suppliers.id as supplier_id','suppliers.name as supplier_name')
                                         ->where('receipts.company_id',Auth::user()->company_id)
                                         ->where('receipts.id','=',$request->id)->first();

        return response()->json($data);
    }
}
