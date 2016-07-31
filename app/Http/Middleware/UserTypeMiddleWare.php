<?php

namespace App\Http\Middleware;

use Closure;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$objectToAccess)
    {
        $usertype = $request->user()->userType->type;
        if($objectToAccess == 'users' || $objectToAccess == 'usertype' 
                || $objectToAccess == 'homeownermember' || $objectToAccess == 'homeownerinfo'){
            if($usertype === 'Administrator')
                return $next($request);
        }elseif($objectToAccess == 'reports' || $objectToAccess == 'journal' 
                || $objectToAccess == 'asset' || $objectToAccess == 'accounttitle'
                || $objectToAccess == 'accountinformation') {
            if($usertype === 'Administrator' || $usertype === 'Accountant')
                return $next($request);
        }elseif($objectToAccess == 'receipts' || $objectToAccess == 'invoice' 
                || $objectToAccess == 'expense') {
            if($usertype === 'Administrator' || $usertype === 'Accountant' || $usertype === 'Cashier')
                return $next($request);
        }elseif($objectToAccess == 'announcement'){
            if($usertype === 'Administrator' || $usertype === 'Guest')
                return $next($request);
        }

        return view('errors.503');
        
    }
}
