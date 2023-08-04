<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChartsController extends Controller
{
    public function getAllMonths()
    {
        $month_array    =   array();
        $month_array    =   ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // $sold_Products= \App\Models\SoldProduct::orderBy('created_at','ASC')->pluck('created_at');
        // if (! empty($sold_Products)) {
        //     foreach ($sold_Products as $key => $month) {
        //         $date   =   new \DateTime($month);
        //         $month_no   =   $date->format('m');
        //         $month_name   =   $date->format('M');
        //         $month_array[$month_no]=$month_name;
        //     }
        // }
        return $month_array;
    }

    public function getMonthlyProductCount($month)
    {
        $product_count  =   \App\Models\SoldProduct::whereMonth('created_at', '=', $month)->where('company_id', '=', Auth::user()->company_id)
            ->whereYear('created_at', date('Y'))->select('*')->sum('quantity');
        return $product_count;
    }

    public function getMonthlyProductIncome($month)
    {
        $product_income  =   \App\Models\SoldProduct::whereMonth('created_at', '=', $month)->where('company_id', '=', Auth::user()->company_id)
            ->whereYear('created_at', date('Y'))->select('*')->sum('total_amount');
        return $product_income;
    }

    public function getMonthlyData()
    {
        $monthly_product_sold_array     =   array();
        $monthly_product_count_array    =   array();
        $monthly_product_income_array   =   array();
        $month_name_array    =   array();
        $month_array    =   $this->getAllMonths();
        if (!empty($month_array)) {
            foreach ($month_array as $month_no => $month_name) {
                // get all product sold per month
                $monthly_product            =   $this->getMonthlyProductCount($month_no + 1);
                $monthly_product_income     =   $this->getMonthlyProductIncome($month_no + 1);

                array_push($monthly_product_count_array, $monthly_product);
                array_push($monthly_product_income_array, $monthly_product_income);
                array_push($month_name_array, $month_name);
            }
        }
        // max number
        $max_no =   max($monthly_product_count_array);
        $max    =   round(($max_no + 10 / 2) / 10) * 10;

        $month_array    =   $this->getAllMonths();
        $monthly_product_sold_array =   array(
            'months'    =>  $month_name_array,
            'product_count'    =>  $monthly_product_count_array,
            'product_income'    =>  $monthly_product_income_array,
            'max'           =>  $max,
        );

        return json_encode($monthly_product_sold_array);
    }
}
