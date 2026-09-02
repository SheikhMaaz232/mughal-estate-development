<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckControllerPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        [$controller, $method] = $this->controllerAction($request);
        $module = $this->moduleFor($controller);

        if (!$module) {
            return $next($request);
        }

        $permission = $module . '.' . $this->actionFor($method);

        abort_unless($user->can($permission), 403, 'Unauthorized action.');

        return $next($request);
    }

    private function controllerAction(Request $request): array
    {
        $action = $request->route()?->getActionName() ?? '';

        if (str_contains($action, '@')) {
            return explode('@', $action, 2);
        }

        return ['', 'view'];
    }

    private function moduleFor(string $controller): ?string
    {
        $modules = [
            'App\\Http\\Controllers\\Admin\\' => 'admin',
            'App\\Http\\Controllers\\ConstructionModule\\' => 'construction',
            'App\\Http\\Controllers\\PurchaseModule\\' => 'procurement',
            'App\\Http\\Controllers\\SaleModule\\' => 'sales',
            'App\\Http\\Controllers\\Registration\\' => 'registration',
            'App\\Http\\Controllers\\Reports\\' => 'reports',
            'App\\Http\\Controllers\\LandPurchase\\' => 'land',
            'App\\Http\\Controllers\\LandRegistration\\' => 'land',
            'App\\Http\\Controllers\\' => 'general',
            'Modules\\Payroll\\App\\Http\\Controllers\\' => 'payroll',
        ];

        foreach ($modules as $namespace => $module) {
            if (Str::startsWith($controller, $namespace)) {
                return $module;
            }
        }

        return null;
    }

    private function actionFor(string $method): string
    {
        return match (Str::lower($method)) {
            'index', 'show', 'view', 'report', 'print', 'select2', 'editpermissions' => 'view',
            'create' => 'create',
            'store', 'initiatepayment', 'recordpayment' => 'create',
            'edit', 'update', 'updatepermissions' => 'edit',
            'destroy', 'delete', 'cancelpayment' => 'delete',
            'approve', 'verify', 'reject', 'post', 'cancel' => 'approve',
            'export', 'exportpayments' => 'export',
            default => 'view',
        };
    }
}
