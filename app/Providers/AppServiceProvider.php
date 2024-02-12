<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\UnavailableProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        view()->composer('manager.includes.layouts.header', function ($view) {
            $count_notification     =   count(\App\Models\SupplierNotify::where('supplier_id', Auth::user()->company_id)->where('status', '0')->get());
            $notifications           =   \App\Models\SupplierNotify::where('supplier_id', Auth::user()->company_id)->where('status', '0')->orderBy('created_at', 'desc')->get();

            $ordersCount   =   Invoice::where('company_id',userInfo()->company_id)->select('status')->get();

            // $pendingProducts    =   UnavailableProduct::where('company_id',userInfo()->company_id)->where('status','pending')->count();

            $view->with('count_notification', $count_notification)
                ->with('notifications', $notifications)
                ->with('orderCount',$ordersCount->count())
                ->with('booking',$ordersCount->where('status','booked')->count())
                ->with('requested',Invoice::where('company_id', userInfo()->company_id)->where('status','requested')->whereDoesntHave('unavailableProducts')->count())
                ->with('priced',$ordersCount->whereIn('status',['Confirmed','priced'])->count())
                ->with('sentToLab',$ordersCount->where('status','sent to supplier')->count())
                ->with('n_a',Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->count())
                // lab orders count
                ->with('sentToLab_',$ordersCount->where('status','sent to lab')->count())
                ->with('production',$ordersCount->where('status','in production')->count())
                ->with('completed',$ordersCount->where('status','completed')->count())
                ->with('delivered',$ordersCount->where('status','delivered')->count())
                ;
                // ->with('pending_product_on_invoice',$pendingProducts);
        });
    }
}
