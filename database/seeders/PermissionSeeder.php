<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Modules with Urdu names
        $modules = [
            'registration' => 'رجسٹریشن',
            'accounts'     => 'اکاؤنٹس',
            'procurement'  => 'پراکیورمنٹ',
            'inventory'    => 'انوینٹری',
            'payroll'      => 'پے رول',
        ];

        // Actions with Urdu translations (action comes second in Urdu)
        $actions = [
            'view'   => 'دیکھیں',
            'create' => 'بنائیں',
            'edit'   => 'ترمیم کریں',
            'delete' => 'حذف کریں',
            'export' => 'ایکسپورٹ کریں',
            'approve'=> 'منظور کریں',
        ];

        foreach ($modules as $moduleKey => $moduleUr) {
            foreach ($actions as $actionKey => $actionUr) {
                Permission::firstOrCreate(
                    ['name' => "{$moduleKey}.{$actionKey}"],
                    [
                        'name_en' => ucfirst($actionKey) . ' ' . ucfirst($moduleKey),
                        'name_ur' => $moduleUr . ' ' . $actionUr,
                        'guard_name' => 'web',
                    ]
                );
            }

            // Payroll-specific permissions
            if ($moduleKey === 'payroll') {
                $extra = [
                    'process'          => 'پروسیس کریں',
                    'generate_payslips'=> 'پے سلپ بنائیں',
                    'manage_tax'       => 'ٹیکس مینج کریں',
                ];
                foreach ($extra as $actionKey => $actionUr) {
                    Permission::firstOrCreate(
                        ['name' => "{$moduleKey}.{$actionKey}"],
                        [
                            'name_en' => ucwords(str_replace('_', ' ', $actionKey)) . ' ' . ucfirst($moduleKey),
                            'name_ur' => $moduleUr . ' ' . $actionUr,
                            'guard_name' => 'web',
                        ]
                    );
                }
            }

            // Inventory-specific permissions
            if ($moduleKey === 'inventory') {
                $extra = [
                    'adjust'  => 'ایڈجسٹ کریں',
                    'transfer'=> 'منتقل کریں',
                    'audit'   => 'آڈٹ کریں',
                ];
                foreach ($extra as $actionKey => $actionUr) {
                    Permission::firstOrCreate(
                        ['name' => "{$moduleKey}.{$actionKey}"],
                        [
                            'name_en' => ucfirst($actionKey) . ' ' . ucfirst($moduleKey),
                            'name_ur' => $moduleUr . ' ' . $actionUr,
                            'guard_name' => 'web',
                        ]
                    );
                }
            }
        }

        // Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['name_en' => 'Super Admin', 'name_ur' => 'سپر ایڈمن', 'guard_name' => 'web']
        );
        $adminRole->givePermissionTo(Permission::all());

        $accountantRole = Role::firstOrCreate(
            ['name' => 'accountant'],
            ['name_en' => 'Accountant', 'name_ur' => 'اکاؤنٹنٹ', 'guard_name' => 'web']
        );
        $accountantRole->givePermissionTo([
            'accounts.view', 'accounts.create', 'accounts.edit', 'accounts.export',
            'payroll.view', 'payroll.process', 'payroll.generate_payslips'
        ]);

        $procurementRole = Role::firstOrCreate(
            ['name' => 'procurement-officer'],
            ['name_en' => 'Procurement Officer', 'name_ur' => 'پراکیورمنٹ آفیسر', 'guard_name' => 'web']
        );
        $procurementRole->givePermissionTo([
            'procurement.view', 'procurement.create', 'procurement.edit', 'procurement.approve'
        ]);

        $inventoryRole = Role::firstOrCreate(
            ['name' => 'inventory-manager'],
            ['name_en' => 'Inventory Manager', 'name_ur' => 'انوینٹری منیجر', 'guard_name' => 'web']
        );
        $inventoryRole->givePermissionTo([
            'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.adjust', 'inventory.transfer'
        ]);

        $payrollRole = Role::firstOrCreate(
            ['name' => 'payroll-specialist'],
            ['name_en' => 'Payroll Specialist', 'name_ur' => 'پے رول اسپیشلسٹ', 'guard_name' => 'web']
        );
        $payrollRole->givePermissionTo([
            'payroll.view', 'payroll.create', 'payroll.edit', 'payroll.process', 'payroll.generate_payslips'
        ]);
    }
}
