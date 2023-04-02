<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use \App\Mail\CustomerInvoiceMail;
use Illuminate\Http\Request;
use Crypt;
use Auth;

class CustomerInvoiceMailController extends Controller
{
    public function sendInvoice($client)
    {
        $client_id          =   Crypt::decrypt($client);

        $client=\App\Models\Customer::select('name','company_id','phone')->first();

		$company=\App\Models\CompanyInformation::where('id',Auth::user()->company_id)->select('*')->first();

        $customerInvoice    =   \App\Models\Invoice::where('client_id','=',$client_id)->where('company_id','=',Auth::user()->company_id)
                                ->where('status','=','completed')
                                ->where('payment','=',NULL)->select('*')
                                ->get();

        $tot    =   0;

        foreach ($customerInvoice as $item)
        {
            $tot=$tot+$item->total_amount;
        }
        \Mail::to('mfiston2020@gmail.com')->send(new CustomerInvoiceMail($customerInvoice,$client,$company,$tot));

        return redirect()->back()->with('successMsg','Invoice was successfully sent to customer');

        // return view('manager.customEmail.customerinvoice',compact('customerInvoice','client'));
    }
}
