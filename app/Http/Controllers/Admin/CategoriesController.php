<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CategoryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories =   \App\Models\Category::all();
        return view('admin.category.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'category_name'=>'required | min:3',
        ]);

        $category   =   new \App\Models\Category();

        $category->name     =   $request->category_name;
        $category->company_id   =   Auth::user()->company_id;
        try {
            $category->save();
            return redirect()->route('admin.category')->with('successMsg','Category Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }

    public function delete($id)
    {
        $detail =   \App\Models\Category::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Category Is Successfully Removed');
        }
        catch (\Throwable $th)
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!'.$th);
        }
    }
}
