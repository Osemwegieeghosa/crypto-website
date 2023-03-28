<?php

// app/Http/Middleware/VerifyKYC.php

namespace App\Http\Middleware;

use Closure;

class VerifyKYC
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_kyc_verified) {
            return $next($request);
        }

        return redirect('/kyc')->with('error', 'Please complete KYC verification to continue.');
    }
}
