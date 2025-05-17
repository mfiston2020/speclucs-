<?php

namespace App\Providers;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
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
        Model::automaticallyEagerLoadRelationships();

        view()->composer('manager.includes.layouts.header', function ($view) {

            $ordersCount            =   Invoice::where('company_id',userInfo()->company_id)->select('status')->get();
            $ordersCountOutside     =   Invoice::where('supplier_id',userInfo()->company_id)->where('status','<>','canceled')->select('status')->get();

            $requested  =   Invoice::where('company_id', userInfo()->company_id)->whereDoesntHave('unavailableProducts')->where('status','requested')->count() + Invoice::where('supplier_id', userInfo()->company_id)->where('status','requested')->whereDoesntHave('unavailableProducts')->count();


            $view->with('from_out',$ordersCountOutside->count())
                ->with('orderCount',$ordersCount->count())
                ->with('booking',$ordersCount->whereIn('status',['Booked','booked'])->count() + $ordersCountOutside->where('status','booked')->count())
                ->with('requested',$requested)
                // ->with('requested',Invoice::where('company_id', userInfo()->company_id)->where('status','requested')->whereDoesntHave('unavailableProducts')->count())
                ->with('priced',$ordersCount->whereIn('status',['Confirmed','priced'])->count() + $ordersCountOutside->whereIn('status',['Confirmed','priced'])->count())
                ->with('sentToLab',$ordersCount->where('status','sent to supplier')->count()+$ordersCountOutside->where('status','sent to supplier')->count())
                ->with('n_a',Invoice::where('company_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->count() + Invoice::where('supplier_id', userInfo()->company_id)->where('status', 'requested')->orderBy('id', 'desc')->has('unavailableproducts')->count())
                // lab orders count
                ->with('sentToLab_',$ordersCount->where('status','sent to lab')->count()+$ordersCountOutside->where('status','sent to lab')->count())
                ->with('production',$ordersCount->where('status','in production')->count()+$ordersCountOutside->where('status','in production')->count())
                ->with('completed',$ordersCount->where('status','completed')->count()+$ordersCountOutside->where('status','completed')->count())
                ->with('delivered',$ordersCount->where('status','delivered')->count()+$ordersCountOutside->where('status','delivered')->count())
                ;
        });
    }
}
