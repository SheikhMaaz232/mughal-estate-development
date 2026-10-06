<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'registration' => ['Registration', 'رجسٹریشن'],
            'accounts' => ['Accounts', 'اکاؤنٹس'],
            'dashboard' => ['Dashboard', 'ڈیش بورڈ'],
            'dashboard-statistic' => ['Dashboard Statistics', 'ڈیش بورڈ کے اعداد و شمار'],
            'procurement' => ['Procurement', 'پراکیورمنٹ'],
            'inventory' => ['Inventory', 'انوینٹری'],
            'payroll' => ['Payroll', 'پے رول'],
            'admin' => ['Administration', 'ایڈمنسٹریشن'],
            'construction' => ['Construction', 'تعمیرات'],
            'sales' => ['Sales', 'فروخت'],
            'reports' => ['Reports', 'رپورٹس'],
            'land' => ['Land', 'زمین'],
            'general' => ['General', 'عمومی'],
            'users' => ['Users', 'صارفین'],
            'user' => ['User', 'صارف'],
            'itemRegistration' => ['Item Registration', 'آئٹم رجسٹریشن'],
            'parties' => ['Parties', 'فریقین'],
            'party' => ['Party', 'فریق'],
            'companies' => ['Companies', 'کمپنیاں'],
            'relations' => ['Relations', 'رشتے'],
            'groups' => ['Groups', 'گروپس'],
            'projects' => ['Projects', 'پروجیکٹس'],
            'cities' => ['Cities', 'شہر'],
            'residentials' => ['Residentials', 'رہائشی علاقے'],
            'banks' => ['Banks', 'بینک'],
            'periods' => ['Periods', 'مدتیں'],
            'schedule-types' => ['Schedule Types', 'شیڈول کی اقسام'],
            'casts' => ['Casts', 'ذاتیں'],
            'warehouses' => ['Warehouses', 'گودام'],
            'units' => ['Units', 'اکائیاں'],
            'tehsils' => ['Tehsils', 'تحصیلیں'],
            'areas' => ['Areas', 'علاقے'],
            'occupation-types' => ['Occupation Types', 'پیشوں کی اقسام'],
            'phase-types' => ['Phase Types', 'مرحلے کی اقسام'],
            'departments' => ['Departments', 'شعبے'],
            'registry-types' => ['Registry Types', 'رجسٹری کی اقسام'],
            'road-categories' => ['Road Categories', 'سڑکوں کی اقسام'],
            'road-specifications' => ['Road Specifications', 'سڑکوں کی خصوصیات'],
            'chart_of_account' => ['Chart of Accounts', 'اکاؤنٹس کا چارٹ'],
            'main_head' => ['Main Heads', 'مین ہیڈز'],
            'control_head' => ['Control Heads', 'کنٹرول ہیڈز'],
            'sub_head' => ['Sub Heads', 'سب ہیڈز'],
            'sub_sub_head' => ['Sub Sub Heads', 'سب سب ہیڈز'],
            'sub_sub_sub_head' => ['Sub Sub Sub Heads', 'سب سب سب ہیڈز'],
            'products' => ['Products', 'مصنوعات'],
            'detailAccounts' => ['Detail Account Tree', 'تفصیلی اکاؤنٹ ٹری'],
            'detail_accounts' => ['Detail Account Records', 'تفصیلی اکاؤنٹس کے ریکارڈ'],
            'TreeView' => ['Account Tree View', 'اکاؤنٹس کا درخت'],
            'vouchers' => ['Vouchers', 'واؤچرز'],
            'bpv' => ['Bank Payment Vouchers', 'بینک ادائیگی واؤچرز'],
            'brv' => ['Bank Receipt Vouchers', 'بینک رسید واؤچرز'],
            'brv-approval' => ['Bank Receipt Voucher Approval', 'بینک رسید واؤچر کی منظوری'],
            'cpv' => ['Cash Payment Vouchers', 'نقد ادائیگی واؤچرز'],
            'crv' => ['Cash Receipt Vouchers', 'نقد رسید واؤچرز'],
            'jv' => ['Journal Vouchers', 'جرنل واؤچرز'],
            'partyLedger' => ['Party Ledger', 'فریق کا لیجر'],
            'booking-module' => ['Booking Module', 'بکنگ ماڈیول'],
            'purchase-module' => ['Purchase Module', 'خریداری ماڈیول'],
            'sale-module' => ['Sale Module', 'فروخت ماڈیول'],
            'construction-module' => ['Construction Module', 'تعمیراتی ماڈیول'],
            'land-module' => ['Land Module', 'زمین ماڈیول'],
            'executive-reports' => ['Executive Reports', 'انتظامی رپورٹس'],
            'audit-log' => ['Audit Log', 'آڈٹ لاگ'],
        ];

        $actions = [
            'index' => ['Index', 'فہرست'],
            'list' => ['List', 'فہرست دیکھیں'],
            'view' => ['View', 'دیکھیں'],
            'show' => ['Show', 'تفصیل دیکھیں'],
            'create' => ['Create', 'بنائیں'],
            'edit' => ['Edit', 'ترمیم کریں'],
            'delete' => ['Delete', 'حذف کریں'],
            'export' => ['Export', 'ایکسپورٹ کریں'],
            'approve' => ['Approve', 'منظور کریں'],
            'print' => ['Print', 'پرنٹ کریں'],
        ];

        foreach ($modules as $module => [$moduleEnglish, $moduleUrdu]) {
            foreach ($actions as $action => [$actionEnglish, $actionUrdu]) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}", 'guard_name' => 'web'],
                    [
                        'name_en' => "{$actionEnglish} {$moduleEnglish}",
                        'name_ur' => "{$moduleUrdu} - {$actionUrdu}",
                    ]
                );
            }
        }

        $additionalActions = [
            'payroll' => [
                'process' => ['Process Payroll', 'پے رول پروسیس کریں'],
                'generate_payslips' => ['Generate Payslips', 'پے سلپس بنائیں'],
                'manage_tax' => ['Manage Payroll Tax', 'پے رول ٹیکس کا انتظام کریں'],
            ],
            'inventory' => [
                'adjust' => ['Adjust Inventory', 'انوینٹری ایڈجسٹ کریں'],
                'transfer' => ['Transfer Inventory', 'انوینٹری منتقل کریں'],
                'audit' => ['Audit Inventory', 'انوینٹری آڈٹ کریں'],
            ],
            'users' => [
                'reset_password' => ['Reset User Password', 'صارف کا پاس ورڈ تبدیل کریں'],
            ],
        ];

        foreach ($additionalActions as $module => $moduleActions) {
            foreach ($moduleActions as $action => [$nameEnglish, $nameUrdu]) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}", 'guard_name' => 'web'],
                    [
                        'name_en' => $nameEnglish,
                        'name_ur' => $nameUrdu,
                    ]
                );
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
