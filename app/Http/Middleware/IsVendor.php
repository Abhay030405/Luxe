<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has vendor role
        if (! auth()->check() || ! auth()->user()->hasVendorRole()) {
            abort(403, 'Unauthorized access. Vendor privileges required.');
        }

        // Additionally check if user has a vendor account
        if (! auth()->user()->isVendor()) {
            return redirect()->route('vendor.application.create')
                ->with('error', 'Please apply for a vendor account first.');
        }

        return $next($request);
    }
}
