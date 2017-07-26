<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthor
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
        $post = $request->route('post');
        if($post){
            if(!$post->isAuthor(Auth::id())){
                session()->flash('error', 'You are not the author!');
                return redirect('post');
            }
        }
        return $next($request);
    }
}
