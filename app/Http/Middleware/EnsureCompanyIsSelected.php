<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyIsSelected
{
    public function handle(Request $request, Closure $next)
    {
        // Skip for routes that don't require company selection
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        // If user is logged in but doesn't have a company selected
        if (Auth::check() && !$this->hasCompanySelected()) {
            // Clear any potentially stale session data
            session()->forget('selected_company_id');

            return redirect()->route('company.select.form')
                ->with('message', 'Please select a company to continue');
        }

        return $next($request);
    }

    /**
     * Check if user has a company selected
     */
    protected function hasCompanySelected(): bool
    {
        return session()->has('selected_company_id')
            && !empty(session('selected_company_id'));
    }

    /**
     * Determine if the request should skip company selection check
     */
    protected function shouldSkip(Request $request): bool
    {
        $skipRoutes = [
            'company.select.form',
            'company.select',
            'logout',
            'login',
            // Add any other routes that shouldn't check for company selection
        ];

        return in_array($request->route()->getName(), $skipRoutes);
    }
}
