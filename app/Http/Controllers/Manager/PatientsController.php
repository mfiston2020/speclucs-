<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PatientsController extends Controller
{
    public function index()
    {
        $patients   =   \App\Models\Patient::where('company_id',Auth::user()->company_id)->get();
        return view('manager.patient.index',compact('patients'));
    }

    public function add()
    {
        return view('manager.patient.create');
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'firstname'=>'required',
            'lastname'=>'required',
            'birthdate'=>'required',
        ]);

        $patient    =   new \App\Models\Patient();
        $patient_count  =   count(\App\Models\Patient::where('company_id',Auth::user()->company_id)->get());

        $patient->company_id    =   Auth::user()->company_id;
        $patient->firstname     =   $request->firstname;
        $patient->lastname      =   $request->lastname;
        $patient->phone         =   $request->phone;
        $patient->province      =   $request->province;
        $patient->district      =   $request->district;
        $patient->sector        =   $request->sector;
        $patient->cell          =   $request->cell;
        $patient->village       =   '';
        $patient->birthdate     =   $request->birthdate;
        $patient->id_number     =   $request->id_number;
        $patient->patient_number=   $patient_count+1;
        $patient->status        =   $request->status;
        $patient->father_name   =   $request->father_name;
        $patient->mother_name   =   $request->mother_name;
        $patient->referral      =   $request->referral;

        try {
            $patient->save();

            return redirect()->route('manager.patients')->with('successMsg','Patient successfully saved!');
        } catch (\Throwable $th) {
            
            return redirect()->back()->with('errorMsg','Something Went Wrong!' );
        }
    }

    public function detail($id)
    {
        $patient    =   \App\Models\Patient::findOrFail(Crypt::decrypt($id));
        $files      =   \App\Models\PatientFile::where('patient_id',$patient->id)->orderBy('created_at','DESC')->get();
        return view('manager.patient.detail',compact('patient','files'));
    }

    public function diagnose($id)
    {
        $insurance  =   \App\Models\Insurance::where('company_id',Auth::user()->company_id)->get();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();
        $patient    =   \App\Models\Patient::findOrFail(Crypt::decrypt($id));

        $complaint  =   \App\Models\Complaint::where('company_id',Auth::user()->company_id)->get();
        $history    =   \App\Models\History::where('company_id',Auth::user()->company_id)->get();

        $exams      =   \App\Models\Exam::where('company_id',Auth::user()->company_id)->get();

        return view('manager.patient.diagnosis',compact('lens_types','chromatics','coatings','index','patient','complaint','history','exams','insurance'));
    }

    public function diagnoseSave(Request $request)
    {
        $number =   count(\App\Models\PatientFile::where('company_id',Auth::user()->company_id)->get());

        $diagnose   =   new \App\Models\PatientFile();

        $complaint  =   null;
        $history    =   null;
        $exam_      =   null;


        // ============
        if ($request->exams==null) 
        {
            $exam_  =   null;
        }
        else
        {
            $exam_  =   implode(',',$request->exams);
        }

        $diagnose->company_id                   =   Auth::user()->company_id;
        $diagnose->user_id                      =   Auth::user()->id;
        $diagnose->patient_id                   =   $request->patient_id;
        $diagnose->file_number                  =   $number+1;
        $diagnose->insurance_id                 =   $request->insurance;
        $diagnose->final_coating                =   $request->final_coating;
        // $diagnose->complaint                    =   $complaint;
        // $diagnose->history                      =   $history;
        $diagnose->unaided_right                =   $request->unaided_right;
        $diagnose->unaided_left                 =   $request->unaided_left;
        $diagnose->pinhole_right                =   $request->pinhole_right;
        $diagnose->pinhole_left                 =   $request->pinhole_left;
        $diagnose->current_lens_type            =   $request->current_lens_type;
        $diagnose->current_index                =   $request->current_index;
        $diagnose->current_chromatics           =   $request->current_chromatics;
        $diagnose->current_coating              =   $request->current_coating;

        $diagnose->current_sphere               =   $request->current_sphere_right;
        $diagnose->current_cylinder             =   $request->current_cylinder_right;
        $diagnose->current_axis                 =   $request->current_axis_right;
        $diagnose->current_addition             =   $request->current_addition_right;
        $diagnose->current_6                    =   $request->current_6_right;

        $diagnose->current_sphere_left          =   $request->current_sphere_left;
        $diagnose->current_cylinder_left        =   $request->current_cylinder_left;
        $diagnose->current_axis_left            =   $request->current_axis_left;
        $diagnose->current_addition_left        =   $request->current_addition_left;
        $diagnose->current_6_left               =   $request->current_6_left;

        $diagnose->subjective_lens_type         =   $request->subjective_lens_type;
        $diagnose->subjective_index             =   $request->subjective_index;
        $diagnose->subjective_chromatics        =   $request->subjective_chromatics;
        $diagnose->subjective_coating           =   $request->subjective_coating;
        $diagnose->subjective_sphere_right      =   $request->subjective_sphere_right;
        $diagnose->subjective_cylinder_right    =   $request->subjective_cylinder_right;
        $diagnose->subjective_axis_right        =   $request->subjective_axis_right;
        $diagnose->subjective_addition_right    =   $request->subjective_addition_right;
        $diagnose->subjective_6_right           =   $request->subjective_6_right;
        $diagnose->subjective_sphere_left       =   $request->subjective_sphere_left;
        $diagnose->subjective_cylinder_left     =   $request->subjective_cylinder_left;
        $diagnose->subjective_axis_left         =   $request->subjective_axis_left;
        $diagnose->subjective_addition_left     =   $request->subjective_addition_left;
        $diagnose->subjective_6                 =   $request->subjective_6;
        $diagnose->final_lens_type              =   $request->final_lens_type;
        $diagnose->final_index                  =   $request->final_index;
        $diagnose->final_chromatics             =   $request->final_chromatics;
        $diagnose->final_sphere_right           =   $request->final_sphere_right;
        $diagnose->final_cylinder_right         =   $request->final_cylinder_right;
        $diagnose->final_axis_right             =   $request->final_axis_right;
        $diagnose->final_addition_right         =   $request->final_addition_right;
        $diagnose->final_6_right                =   $request->final_6_right;
        $diagnose->final_pd_right               =   $request->final_pd_right;
        $diagnose->final_sphere_left            =   $request->final_sphere_left;
        $diagnose->final_cylinder_left          =   $request->final_cylinder_left;
        $diagnose->final_axis_left              =   $request->final_axis_left;
        $diagnose->final_addition_left          =   $request->final_addition_left;
        $diagnose->final_6_left                 =   $request->final_6_left;
        $diagnose->final_pd_left                =   $request->final_pd_left;
        $diagnose->lids_right                   =   $request->lids_right;
        $diagnose->lids_left                    =   $request->lids_left;
        $diagnose->conjuctiva_right             =   $request->conjuctiva_right;
        $diagnose->conjuctiva_left              =   $request->conjuctiva_left;
        $diagnose->cornea_right                 =   $request->cornea_right;
        $diagnose->cornea_left                  =   $request->cornea_left;
        $diagnose->a_c_right                    =   $request->a_c_right;
        $diagnose->a_c_left                     =   $request->a_c_left;
        $diagnose->iris_right                   =   $request->iris_right;
        $diagnose->iris_left                    =   $request->iris_left;
        $diagnose->pupil_right                  =   $request->pupil_right;
        $diagnose->pupil_left                   =   $request->pupil_left;
        $diagnose->lens_right                   =   $request->lens_right;
        $diagnose->lens_left                    =   $request->lens_left;
        $diagnose->vitreous_right               =   $request->vitreous_right;
        $diagnose->vitreous_left                =   $request->vitreous_left;
        $diagnose->c_d_right                    =   $request->c_d_right;
        $diagnose->c_d_left                     =   $request->c_d_left;
        $diagnose->a_v_right                    =   $request->a_v_right;
        $diagnose->a_v_left                     =   $request->a_v_left;
        $diagnose->macula_right                 =   $request->macula_right;
        $diagnose->macula_left                  =   $request->macula_left;
        $diagnose->periphery_right              =   $request->periphery_right;
        $diagnose->periphery_left               =   $request->periphery_left;
        $diagnose->iop_right                    =   $request->iop_right;
        $diagnose->iop_left                     =   $request->iop_left;
        $diagnose->exams                        =   $exam_;
        $diagnose->assessment_diagnosis         =   $request->assessment_diagnosis;
        $diagnose->management_treatment         =   $request->management_treatment;
        try 
        {

            $total_amount   =   0;

            // ============
            if ($request->exams==null) 
            {
                $exam_  =   null;
            }
            else
            {
                foreach($request->exams as $exam)
                {
                    $exam_   =   \App\Models\Exam::find($exam);
                    $total_amount   =   $total_amount + $exam_->amount;
                }
            }

            $invoice_number =   count(\App\Models\PrescriptionInvoice::where('company_id',Auth::user()->company_id)->get());

            $diagnose->save();

            // adding complaint into the database
            if ($request->complaint==null) 
            {
                $complaint  =   null;
            }
            else
            {
                foreach ($request->complaint as $key => $complaint) 
                {
                    $complaint_data =   new \App\Models\FileComplaint();

                    $complaint_data->file_id        =   $diagnose->id;
                    $complaint_data->company_id     =   Auth::user()->company_id;
                    $complaint_data->value          =   $complaint;
                    $complaint_data->complaint_id   =   $request->complaint_id[$key];
                    $complaint_data->save();
                }
            }
            // ============
            if ($request->history==null) 
            {
                $history  =   null;
            }
            else
            {
                foreach ($request->history as $key => $history) 
                {
                    $history_data =   new \App\Models\FileHistory();

                    $history_data->file_id      =   $diagnose->id;
                    $history_data->company_id   =   Auth::user()->company_id;
                    $history_data->value        =   $history;
                    $history_data->history_id   =   $request->history_id[$key];
                    $history_data->save();
                }
            }

            $invoice                    =   new \App\Models\PrescriptionInvoice();

            $invoice->patient_id        =   $request->patient_id;
            $invoice->company_id        =   Auth::user()->company_id;
            $invoice->prescription_id   =   $diagnose->id;
            $invoice->invoice_number    =   $invoice_number+1;
            $invoice->amount            =   $total_amount;
            $invoice->due               =   0;
            $invoice->save();

            $patientNumber  =   \App\Models\Patient::find($request->patient_id);
            $company_name   =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
            // <?php

                if ($company_name->can_send_sms==1 && $company_name->sms_quantity>0) 
                {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.mista.io/sms',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS 
                    => array('to' => $patientNumber->phone,'from' => 'SPECLUCS','unicode' => '0','sms' 
                    => 'Hello '.$patientNumber->firstname.', '.$company_name->company_name.' is sending you the invoice of '.$total_amount.'RWF','action' 
                    => 'send-sms'),
                    CURLOPT_HTTPHEADER => array(
                        'x-api-key: ecb697cc-99f3-913e-a618-aae6038c4613-5f82c0d9'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $company_name->sms_quantity =   $company_name->sms_quantity - 1;
                    $company_name->save();
                }
            return redirect()->route('manager.patient.detail',Crypt::encrypt($request->patient_id))->with('successMsg','Patient successfully saved!');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->with('errorMsg','Something Went Wrong!' )->withInput();
        }
    }

    public function delete($id)
    {
        $patient    =   \App\Models\Patient::findOrFail(Crypt::decrypt($id));
        try {
            $patient->delete();

            return redirect()->route('manager.patients')->with('successMsg','Patient successfully deleted!');
        }    catch (\Throwable $th) {
            
            return redirect()->back()->with('errorMsg','Something Went Wrong!' );
        }
    }


    public function fileDetail($id)
    {
        $file       =   \App\Models\PatientFile::find(Crypt::decrypt($id));
        $patient    =   \App\Models\Patient::find($file->patient_id);
        $complaint  =   \App\Models\Complaint::all();
        $history    =   \App\Models\History::all();

        // $complaint_data =   explode(',',$file->complaint);
        // $history_data   =   explode(',',$file->history);
        $complaint_data =   \App\Models\FileComplaint::where('file_id',$file->id)->select('*')->get();
        $history_data =   \App\Models\FileHistory::where('file_id',$file->id)->select('*')->get();

        return view('manager.patient.prescription',compact('file','patient','complaint','history','complaint_data','history_data'));
    }

    public function fileinvoice($id)
    {
        $invoice        =   \App\Models\PrescriptionInvoice::where('prescription_id',Crypt::decrypt($id))->first();
        $patient        =   \App\Models\Patient::findOrFail($invoice->patient_id);
        $prescription   =  explode(',',\App\Models\PatientFile::where('id',$invoice->prescription_id)->pluck('exams')->first());
        $insurance      =  \App\Models\PatientFile::where('id',$invoice->prescription_id)->pluck('insurance_id')->first();

        $exams          =   array();

        $grandT =   0;

        if ($prescription[0]==null) 
        {
            return redirect()->back()->with('warningMsg','This File have no Exams');
        } 
        else 
        {
            foreach ($prescription as $key => $value) 
            {
                $total_amount   =   0;
                $exam           =   \App\Models\Exam::find($value);
                $percentage     =   \App\Models\InsuranceExam::where('insurance_id',$insurance)->where('exam_id',$exam->id)->where('company_id',Auth::user()->company_id)->pluck("percentage")->first();


                if($percentage==null)
                {
                    $percentage=0;
                    $total_amount      =   0;
                }
                else
                {
                    $total   =  (($exam->amount*$percentage)/100);
                    $total_amount   =   $total_amount + $total;
                }

                $exams[$key]  =   array(
                    'exam_name'=>$exam->exam_name,
                    'percentage'=>$percentage,
                    'amount'=>$exam->amount,
                    'total'=>$total_amount,
                );
                $percentage =   0;

                $grandT =   $grandT+$exam->amount;
            }
        }
        

        // return $grandT;

        return  view('manager.patient.invoice',compact('invoice','patient','prescription','exams','grandT'));
    }

    public function all_invoices()
    {
        $patient_invoices   =   \App\Models\PrescriptionInvoice::where('company_id',Auth::user()->company_id)->get();
        return view('manager.patient.invoices',compact('patient_invoices'));
        // return $patient_invoices;
    }

    public function filedelete($id)
    {
        $file   =   \App\Models\PatientFile::find(Crypt::decrypt($id));
        try 
        {
            $file->delete();
            return redirect()->back()->with('successMsg','Patient File successfully Deleted!');
        } 
        catch (\Throwable $th)
        {
            return redirect()->back()->with('errorMsg','Something Went Wrong!' );
        }
    }

    public function final_prescription($id)
    {
        $file   =   \App\Models\PatientFile::find(Crypt::decrypt($id));
        $patient    =   \App\Models\Patient::find($file->patient_id);

        return view('manager.patient.final_prescription',compact('file','patient'));
    }

    function file_edit($id)
    {
        
        $file   =   \App\Models\PatientFile::find(Crypt::decrypt($id));

        $patient    =   \App\Models\Patient::findOrFail($file->patient_id);

        $insurance  =   \App\Models\Insurance::where('company_id',Auth::user()->company_id)->get();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();

        $complaint  =   \App\Models\Complaint::where('company_id',Auth::user()->company_id)->get();
        $history    =   \App\Models\History::where('company_id',Auth::user()->company_id)->get();

        $exams      =   \App\Models\Exam::where('company_id',Auth::user()->company_id)->get();
        
        $file_exams =   explode(',',$file->exams);
        // return $file_exams;

        return view('manager.patient.edit',compact('file','patient','lens_types','chromatics','coatings','index','complaint','history','exams','insurance','file_exams'));
    }

    function file_update(Request $request)
    {
        // $number =   count(\App\Models\PatientFile::where('company_id',Auth::user()->company_id)->get());

        $diagnose   =   \App\Models\PatientFile::find($request->file_id);

        $complaint  =   null;
        $history    =   null;
        $exam_      =   null;


        // ============
        if ($request->exams==null) 
        {
            $exam_  =   null;
        }
        else
        {
            $exam_  =   implode(',',$request->exams);
        }

        $diagnose->company_id                   =   Auth::user()->company_id;
        $diagnose->user_id                      =   Auth::user()->id;
        $diagnose->patient_id                   =   $request->patient_id;
        // $diagnose->file_number                  =   $number+1;
        $diagnose->insurance_id                 =   $request->insurance;
        $diagnose->final_coating                =   $request->final_coating;
        // $diagnose->complaint                    =   $complaint;
        // $diagnose->history                      =   $history;
        $diagnose->unaided_right                =   $request->unaided_right;
        $diagnose->unaided_left                 =   $request->unaided_left;
        $diagnose->pinhole_right                =   $request->pinhole_right;
        $diagnose->pinhole_left                 =   $request->pinhole_left;
        $diagnose->current_lens_type            =   $request->current_lens_type;
        $diagnose->current_index                =   $request->current_index;
        $diagnose->current_chromatics           =   $request->current_chromatics;
        $diagnose->current_coating              =   $request->current_coating;

        $diagnose->current_sphere               =   $request->current_sphere_right;
        $diagnose->current_cylinder             =   $request->current_cylinder_right;
        $diagnose->current_axis                 =   $request->current_axis_right;
        $diagnose->current_addition             =   $request->current_addition_right;
        $diagnose->current_6                    =   $request->current_6_right;

        $diagnose->current_sphere_left          =   $request->current_sphere_left;
        $diagnose->current_cylinder_left        =   $request->current_cylinder_left;
        $diagnose->current_axis_left            =   $request->current_axis_left;
        $diagnose->current_addition_left        =   $request->current_addition_left;
        $diagnose->current_6_left               =   $request->current_6_left;

        $diagnose->subjective_lens_type         =   $request->subjective_lens_type;
        $diagnose->subjective_index             =   $request->subjective_index;
        $diagnose->subjective_chromatics        =   $request->subjective_chromatics;
        $diagnose->subjective_coating           =   $request->subjective_coating;
        $diagnose->subjective_sphere_right      =   $request->subjective_sphere_right;
        $diagnose->subjective_cylinder_right    =   $request->subjective_cylinder_right;
        $diagnose->subjective_axis_right        =   $request->subjective_axis_right;
        $diagnose->subjective_addition_right    =   $request->subjective_addition_right;
        $diagnose->subjective_6_right           =   $request->subjective_6_right;
        $diagnose->subjective_sphere_left       =   $request->subjective_sphere_left;
        $diagnose->subjective_cylinder_left     =   $request->subjective_cylinder_left;
        $diagnose->subjective_axis_left         =   $request->subjective_axis_left;
        $diagnose->subjective_addition_left     =   $request->subjective_addition_left;
        $diagnose->subjective_6                 =   $request->subjective_6;
        $diagnose->final_lens_type              =   $request->final_lens_type;
        $diagnose->final_index                  =   $request->final_index;
        $diagnose->final_chromatics             =   $request->final_chromatics;
        $diagnose->final_sphere_right           =   $request->final_sphere_right;
        $diagnose->final_cylinder_right         =   $request->final_cylinder_right;
        $diagnose->final_axis_right             =   $request->final_axis_right;
        $diagnose->final_addition_right         =   $request->final_addition_right;
        $diagnose->final_6_right                =   $request->final_6_right;
        $diagnose->final_pd_right               =   $request->final_pd_right;
        $diagnose->final_sphere_left            =   $request->final_sphere_left;
        $diagnose->final_cylinder_left          =   $request->final_cylinder_left;
        $diagnose->final_axis_left              =   $request->final_axis_left;
        $diagnose->final_addition_left          =   $request->final_addition_left;
        $diagnose->final_6_left                 =   $request->final_6_left;
        $diagnose->final_pd_left                =   $request->final_pd_left;
        $diagnose->lids_right                   =   $request->lids_right;
        $diagnose->lids_left                    =   $request->lids_left;
        $diagnose->conjuctiva_right             =   $request->conjuctiva_right;
        $diagnose->conjuctiva_left              =   $request->conjuctiva_left;
        $diagnose->cornea_right                 =   $request->cornea_right;
        $diagnose->cornea_left                  =   $request->cornea_left;
        $diagnose->a_c_right                    =   $request->a_c_right;
        $diagnose->a_c_left                     =   $request->a_c_left;
        $diagnose->iris_right                   =   $request->iris_right;
        $diagnose->iris_left                    =   $request->iris_left;
        $diagnose->pupil_right                  =   $request->pupil_right;
        $diagnose->pupil_left                   =   $request->pupil_left;
        $diagnose->lens_right                   =   $request->lens_right;
        $diagnose->lens_left                    =   $request->lens_left;
        $diagnose->vitreous_right               =   $request->vitreous_right;
        $diagnose->vitreous_left                =   $request->vitreous_left;
        $diagnose->c_d_right                    =   $request->c_d_right;
        $diagnose->c_d_left                     =   $request->c_d_left;
        $diagnose->a_v_right                    =   $request->a_v_right;
        $diagnose->a_v_left                     =   $request->a_v_left;
        $diagnose->macula_right                 =   $request->macula_right;
        $diagnose->macula_left                  =   $request->macula_left;
        $diagnose->periphery_right              =   $request->periphery_right;
        $diagnose->periphery_left               =   $request->periphery_left;
        $diagnose->iop_right                    =   $request->iop_right;
        $diagnose->iop_left                     =   $request->iop_left;
        $diagnose->exams                        =   $exam_;
        $diagnose->assessment_diagnosis         =   $request->assessment_diagnosis;
        $diagnose->management_treatment         =   $request->management_treatment;
        try 
        {

            $total_amount   =   0;

            // ============
            if ($request->exams==null) 
            {
                $exam_  =   null;
            }
            else
            {
                foreach($request->exams as $exam)
                {
                    $exam_   =   \App\Models\Exam::find($exam);
                    $total_amount   =   $total_amount + $exam_->amount;
                }
            }

            // $invoice_number =   count(\App\Models\PrescriptionInvoice::where('company_id',Auth::user()->company_id)->get());

            $diagnose->save();

            // adding complaint into the database
            if ($request->complaint==null) 
            {
                $complaint  =   null;
            }
            else
            {
                foreach ($request->complaint as $key => $complaint) 
                {
                    $complaint_data =   \App\Models\FileComplaint::find($request->complaint_id[$key]);

                    // $complaint_data->file_id        =   $diagnose->id;
                    $complaint_data->company_id     =   Auth::user()->company_id;
                    $complaint_data->value          =   $complaint;
                    // $complaint_data->complaint_id   =   $request->complaint_id[$key];
                    $complaint_data->save();
                }
            }
            // ============
            if ($request->history==null) 
            {
                $history  =   null;
            }
            else
            {
                foreach ($request->history as $key => $history) 
                {
                    $history_data =   \App\Models\FileHistory::find($request->history_id[$key]);

                    // $history_data->file_id      =   $diagnose->id;
                    $history_data->company_id   =   Auth::user()->company_id;
                    $history_data->value        =   $history;
                    // $history_data->history_id   =   $request->history_id[$key];
                    $history_data->save();
                }
            }

            $invoice                    =   \App\Models\PrescriptionInvoice::where('patient_id',$request->patient_id)->where('prescription_id',$request->file_id)->first();

            $invoice->patient_id        =   $request->patient_id;
            $invoice->company_id        =   Auth::user()->company_id;
            // $invoice->prescription_id   =   $diagnose->id;
            // $invoice->invoice_number    =   $invoice_number+1;
            $invoice->amount            =   $total_amount;
            $invoice->due               =   0;
            $invoice->save();
            
            return redirect()->route('manager.patient.detail',Crypt::encrypt($request->patient_id))->with('successMsg','Patient data Updated!');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->with('errorMsg','Something Went Wrong!' )->withInput();
        }
    }
}
