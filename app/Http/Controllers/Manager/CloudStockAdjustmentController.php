<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Imports\Manager\CloudProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CloudStockAdjustmentController extends Controller
{
    function index()
    {
        return view('manager.productAdjust.import');
    }

    function saveImport(Request $request)
    {
        $this->validate($request, [
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new CloudProductImport(), $request->excelFile);
            // Excel::queueImport(new CloudProductImport(), $request->excelFile);

            $count  =   session('countSkippedImport');

            // if ($count > 0) {
            if (session('errorMsg')) {
                return redirect()->back();
            } else {
                return redirect()->route('manager.product')->with('successMsg', 'Importing successful added ' . $count . ' New Products');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Oops! something Went Wrong!'.$th);
        }
    }
}
