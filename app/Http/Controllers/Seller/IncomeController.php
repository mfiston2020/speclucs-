<?php

namespace App\Http\Controllers\Seller;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $income    =   \App\Models\Transactions::where(['type'=>'income'])->where('company_id',Auth::user()->company_id)->where('user_id',Auth::user()->id)->select('*')->get();
        return view('seller.income.index',compact('income'));
    }

    public function add()
    {
        $payment_method =   \App\Models\PaymentMethod::all();
        return view('seller.income.create',compact('payment_method'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'income_name'=>'required',
            'payment'=>'required',
            'amount'=>'required | integer',
        ]);

        $transaction    =   new \App\Models\Transactions();

        $transaction->user_id           =   Auth::user()->id;
        $transaction->payment_method_id =   $request->payment;
        $transaction->title             =   $request->income_name;
        $transaction->type              =   'income';
        $transaction->amount            =   $request->amount;
        $transaction->company_id        =   Auth::user()->company_id;

        try {
            $transaction->save();
            return redirect()->route('seller.income')->with('successMsg','The Income has been successfully Recoded!');
        } catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }
}
