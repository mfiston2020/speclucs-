<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ClinicSettingController extends Controller
{
    private $prms;
    public function __construct()
    {
        $this->middleware('manager');
        $this->middleware(function ($request, $next) {
            $this->prms = Auth::user()->permissions;
            if ($this->prms!='manager') {
                return redirect()->back()->with('warningMsg','you are not allowed to do this!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $drugs              =   \App\Models\Drug::where('company_id',Auth::user()->company_id)->get();
        $exams              =   \App\Models\Exam::where('company_id',Auth::user()->company_id)->get();
        $insurances         =    \App\Models\Insurance::where('company_id',Auth::user()->company_id)->get();
        $insurance_exams    =   \App\Models\InsuranceExam::where('company_id',Auth::user()->company_id)->get();
        $complaint          =   \App\Models\Complaint::where('company_id',Auth::user()->company_id)->get();
        $histories          =   \App\Models\History::where('company_id',Auth::user()->company_id)->get();

        return view('manager.profile.clinicsettings',compact(
            'drugs','exams','insurances','insurance_exams','complaint','histories'
        ));
    }

    // exam functions
    public function save_clinic_exam(Request $request)
    {
        $this->validate($request,[
            'exam_name'=>'required | string',
            'exam_price'=>'required | integer',
        ]);

        $exam   =   new \App\Models\Exam();
        $exam->exam_name    =   $request->exam_name;
        $exam->amount       =   $request->exam_price;
        $exam->company_id   =   Auth::user()->company_id;
        try {
            $exam->save();
            return redirect()->back()->with('successMsg','Exam Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function save_clinic_exam_remove($id)
    {
        $exam   =   \App\Models\Exam::findOrFail(Crypt::decrypt($id));
        try {
            $exam->delete();
            return redirect()->back()->with('successMsg','Exam Removed Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    // insurance functions
    public function save_clinic_insurance(Request $request)
    {
        $this->validate($request,[
            'insurance_name'=>'required | string',
        ]);

        $insurance   =   new \App\Models\Insurance();
        $insurance->insurance_name  =   $request->insurance_name;
        $insurance->company_id      =   Auth::user()->company_id;
        try {
            $insurance->save();
            return redirect()->back()->with('successMsg','insurance Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function save_clinic_insurance_remove($id)
    {
        $insurance   =   \App\Models\Insurance::findOrFail(Crypt::decrypt($id));
        try {
            $insurance->delete();
            return redirect()->back()->with('successMsg','insurance Removed Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    // insurance functions
    public function save_insurance_percentage(Request $request)
    {
        $this->validate($request,[
            'insurance'=>'required',
            'exam'=>'required',
            'percentage'=>'required',
        ]);

        $insurance   =   new \App\Models\InsuranceExam();

        $insurance->exam_id         =   $request->exam;
        $insurance->insurance_id    =   $request->insurance;
        $insurance->percentage      =   $request->percentage;
        $insurance->company_id      =   Auth::user()->company_id;
        try
        {
            $insurance->save();
            return redirect()->back()->with('successMsg','insurance percentage Created Successfully');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function save_insurance_percentage_remove($id)
    {
        $insurance   =   \App\Models\InsuranceExam::findOrFail(Crypt::decrypt($id));
        try {
            $insurance->delete();
            return redirect()->back()->with('successMsg','insurance percentage Removed Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    // complaint functions
    public function save_clinic_complaint(Request $request)
    {
        $this->validate($request,[
            'complaint_name'=>'required',
        ]);

        $complaint   =   new \App\Models\Complaint();

        $complaint->name         =   $request->complaint_name;
        $complaint->company_id      =   Auth::user()->company_id;
        try
        {
            $complaint->save();
            return redirect()->back()->with('successMsg','complaint Created Successfully');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function save_complaint_remove($id)
    {
        $complaint   =   \App\Models\Complaint::findOrFail(Crypt::decrypt($id));
        try {
            $complaint->delete();
            return redirect()->back()->with('successMsg','complaint Removed Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    // history functions
    public function save_clinic_history(Request $request)
    {
        $this->validate($request,[
            'history_name'=>'required',
        ]);

        $history   =   new \App\Models\History();

        $history->name         =   $request->history_name;
        $history->company_id      =   Auth::user()->company_id;
        try
        {
            $history->save();
            return redirect()->back()->with('successMsg','history Created Successfully');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function save_history_remove($id)
    {
        $history   =   \App\Models\History::findOrFail(Crypt::decrypt($id));
        try {
            $history->delete();
            return redirect()->back()->with('successMsg','history Removed Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

}
