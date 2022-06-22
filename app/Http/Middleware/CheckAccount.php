<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Employee;
use Illuminate\Http\Request;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Employee::where('user_id', auth()->id())->exists()){
            return $next($request);
        }
        return redirect()->route('account')->with('incomplete_account', 'incomplete_account');

    }
}
