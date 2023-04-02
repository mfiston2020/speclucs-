<?php

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $income    =   \App\Models\Transactions::where(['type'=>'income'])->where('company_id',Auth::user()->company_id)
                                                ->select('*')->orderBy('created_at','DESC')->get();
        return view('manager.income.index',compact('income'));
    }

    public function add()
    {
        $payment_method =   \App\Models\PaymentMethod::all();
        return view('manager.income.create',compact('payment_method'));
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
            return redirect()->route('manager.income')->with('successMsg','The Income has been successfully Recoded!');
        } catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
