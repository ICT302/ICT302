<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSuperadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->isSuperAdmin())
        {

            return $next($request);
        }
        else
        {
            session()->flash('error', 'Not allowed. Super Admin restricted page!');
            return redirect('post');
        }
    }
}
