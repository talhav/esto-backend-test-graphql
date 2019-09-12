<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckRole
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

        $contains = Str::contains($request->getRequestUri(), 'TransactionMutation');

        if($contains && Auth::user()->is_admin){
            return response()->json(['error' => 'unauthorized_user'], 403);
        }
            return $next($request);

    }
}
