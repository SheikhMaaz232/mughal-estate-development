<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $this->call(ModulePermissionSeeder::class);

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
            'dashboard.view',
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
