<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses    =   \App\Models\Transactions::where(['type'=>'expense'])
                                    ->where('company_id',Auth::user()->company_id)
                                    ->select('*')->orderBy('created_at','DESC')->get();
        return view('manager.expense.index',compact('expenses'));
    }

    public function add()
    {
        $payment_method =   \App\Models\PaymentMethod::all();
        return view('manager.expense.create',compact('payment_method'));
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
            return redirect()->route('manager.expenses')->with('successMsg','The Expense has been successfully Recoded!');
        } catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function delete($id)
    {
        $expense    =   \App\Models\Transactions::find(Crypt::decrypt($id));
        // return $expense;
        try {
            $expense->delete();
            return redirect()->back()->with('successMsg','expense successfully removed');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }
}
