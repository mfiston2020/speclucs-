<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories =   \App\Models\Category::all();
        return view('manager.category.index',compact('categories'));
    }

    public function create()
    {
        return view('manager.category.create');
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
            return redirect()->route('manager.category')->with('successMsg','Category Created Successfully');
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
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!');
        }
    }
}
