<?php

namespace App\Http\Controllers\Seller;

use DB;
use Auth;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses    =   \App\Models\Transactions::where(['type'=>'expense'])->where('company_id',Auth::user()->company_id)
                            ->where('user_id',Auth::user()->id)->select('*')->get();
        return view('seller.expense.index',compact('expenses'));
    }

    public function add()
    {
        $payment_method =   \App\Models\PaymentMethod::where('company_id',Auth::user()->company_id)->select('*')->get();
        return view('seller.expense.create',compact('payment_method'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'expense_name'=>'required',
            'payment'=>'required',
            'amount'=>'required | integer',
        ]);

        $transaction    =   new \App\Models\Transactions();

        $transaction->user_id           =   Auth::user()->id;
        $transaction->payment_method_id =   $request->payment;
        $transaction->title             =   $request->expense_name;
        $transaction->type              =   'expense';
        $transaction->amount            =   '-'.$request->amount;
        $transaction->company_id        =   Auth::user()->company_id;

        try {
            $transaction->save();
            return redirect()->route('seller.expenses')->with('successMsg','The Expense has been successfully Recoded!');
        } catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
