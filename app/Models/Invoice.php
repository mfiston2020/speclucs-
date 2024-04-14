<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =   [
        'client_id',
        'client_name',
        'phone',
        'tin_number',
        'gender',
        'dateOfBirth',
        'status',
        'received_by_lab',
        'sent_to_lab',
        'set_price',
        'sent_to_seller',
        'payment_approval',
        'sent_to_supplier',
        'received_by_seller',
        'received_by_patient',
        'receive_from_supplier',
    ];

    function SoldProduct()
    {
        return $this->hasMany(SoldProduct::class,'invoice_id');
    }

    function sumOfCategorizedproduct()
    {
        $categoriesTotal    = [];

        $lensTotal  = 0;
        $frameTotal = 0;
        $accessoriesTotal= 0;

        $soldP = $this->soldproduct()->where('invoice_id',$this->id)->with('product:id,category_id,price')->select('id','product_id')->get();
        $category   =   Category::select('id','name')->get();

        foreach ($soldP as $key => $value) {

            if ($value->product->category_id== $category->where('name','Lens')->pluck('id')->first()) {
                $categoriesTotal['lens']    = $lensTotal + $value->product->price;
                if ($categoriesTotal || $categoriesTotal['lens']) {
                    $categoriesTotal['lens']    +=$value->product->price;
                }
            }
            if ($value->product->category_id==$category->where('name','Frame')->pluck('id')->first()) {
                $categoriesTotal['frame']   = $frameTotal+$value->product->price;
            }
            if ($value->product->category_id!=$category->where('name','Lens')->pluck('id')->first() && $value->product->category_id!=$category->where('name','Frame')->pluck('id')->first()) {
                $categoriesTotal['accessories'] = $accessoriesTotal+$value->product->price;
            }
        }

        $categoriesTotal['total']   = $categoriesTotal['lens']+$categoriesTotal['frame']+$categoriesTotal['accessories'];
        return $categoriesTotal;
    }

    function totalAmount(){
        return $this->soldproduct()->where('invoice_id',$this->id)->sum('unit_price');
    }

    function company(){
        return $this->belongsTo(CompanyInformation::class,'company_id');
    }

    function supplier(){
        return $this->belongsTo(CompanyInformation::class,'supplier_id');
    }

    function client()
    {
        return $this->belongsTo(Customer::class);
    }

    function unavailableProducts()
    {
        return $this->hasMany(UnavailableProduct::class);
    }

    function isAsuranceInvoice(){
        return $this->hasOne(InsuranceSumaryInvoices::class);
    }

    function hasbeeninvoiced(){
        return InsuranceSumaryInvoices::where('invoice_id',$this->id)->exists();
    }

    function orderrecord(){
        return $this->hasMany(TrackOrderRecord::class,'invoice_id');
    }
}
