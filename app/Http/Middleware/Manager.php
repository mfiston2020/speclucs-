<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->check()) {
            return redirect(route('login'));
        }
        if(Auth::user()->role=='manager')
        {
            return $next($request);
        }
        if(Auth::user()->role == 'client')
        {
            return redirect()->route('client');
        }
        if(Auth::user()->role == 'supplier')
        {
            return redirect()->route('supplier');
        }
        if(Auth::user()->role == 'admin')
        {
            return redirect()->route('admin');
        }
        if(Auth::user()->role == 'seller')
        {
            return redirect()->route('seller');
        }
    }
}
