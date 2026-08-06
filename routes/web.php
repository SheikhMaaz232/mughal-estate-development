<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ConstructionModule\BOQMasterController;
use App\Http\Controllers\ConstructionModule\ConstructionSiteController;
use App\Http\Controllers\ConstructionModule\ContractorBillController;
use App\Http\Controllers\ConstructionModule\ContractorPaymentController;
use App\Http\Controllers\ConstructionModule\TenderController;
use App\Http\Controllers\ConstructionModule\WorkOrderController;
use App\Http\Controllers\ConstructionModule\WorkProgressController;
use App\Http\Controllers\LandPurchase\LandRegistrationController;
use App\Http\Controllers\LandPurchase\RegistryTypeController;
use App\Http\Controllers\LandRegistration\LandController;
use App\Http\Controllers\LandRegistration\LandReportController;
use App\Http\Controllers\LandRegistration\LandTransferController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PurchaseModule\GRNController;
use App\Http\Controllers\PurchaseModule\PurchaseController;
use App\Http\Controllers\PurchaseModule\PurchaseOrderController;
use App\Http\Controllers\PurchaseModule\PurchaseReturnController;
use App\Http\Controllers\Registration\AreaController;
use App\Http\Controllers\Registration\BankController;
use App\Http\Controllers\Registration\BankPaymentVoucherController;
use App\Http\Controllers\Registration\BankReceiptVoucherController;
use App\Http\Controllers\Registration\BookingApplicationController;
use App\Http\Controllers\Registration\BookingReturnController;
use App\Http\Controllers\Registration\CashPaymentVoucherController;
use App\Http\Controllers\Registration\CashReceiptVoucherController;
use App\Http\Controllers\Registration\CastController;
use App\Http\Controllers\Registration\CityController;
use App\Http\Controllers\Registration\ClientInvoiceController;
use App\Http\Controllers\Registration\ClientInvoiceReceiptController;
use App\Http\Controllers\Registration\CompanyController;
use App\Http\Controllers\Registration\CompanySelectionController;
use App\Http\Controllers\Registration\ControlHeadController;
use App\Http\Controllers\Registration\DealerController;
use App\Http\Controllers\Registration\DepartmentController;
use App\Http\Controllers\Registration\DetailAccountController;
use App\Http\Controllers\Registration\GroupController;
use App\Http\Controllers\Registration\ItemController;
use App\Http\Controllers\Registration\JournalVoucherController;
use App\Http\Controllers\Registration\LedgerController;
use App\Http\Controllers\Registration\MainHeadController;
use App\Http\Controllers\Registration\OccupationTypeController;
use App\Http\Controllers\Registration\PhaseDivisionTypeController;
use App\Http\Controllers\Registration\PossessionLetterController;
use App\Http\Controllers\Registration\ProductController;
use App\Http\Controllers\Registration\ProfileController;
use App\Http\Controllers\Registration\ProjectController;
use App\Http\Controllers\Registration\RegisteredPartiesController;
use App\Http\Controllers\Registration\RegistryOrderController;
use App\Http\Controllers\Registration\RelationController;
use App\Http\Controllers\Registration\ResidentialController;
use App\Http\Controllers\Registration\RoadCategoryController;
use App\Http\Controllers\Registration\RoadSpecificationController;
use App\Http\Controllers\Registration\SchedulePeriodController;
use App\Http\Controllers\Registration\ScheduleTypeController;
use App\Http\Controllers\Registration\SubHeadController;
use App\Http\Controllers\Registration\SubSubHeadController;
use App\Http\Controllers\Registration\SubSubSubHeadController;
use App\Http\Controllers\Registration\TehsilController;
use App\Http\Controllers\Registration\UnitController;
use App\Http\Controllers\Registration\UnitRegistrationController;
use App\Http\Controllers\Registration\UserController;
use App\Http\Controllers\Registration\WarehouseController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\SaleModule\SaleInvoiceController;
use Illuminate\Support\Facades\Route;
use Modules\Payroll\App\Http\Controllers\DashboardController;
use Modules\Payroll\App\Http\Controllers\QualificationController;

Route::post('/locale', LocaleController::class)->name('locale.change');


// Route::get('/{locale?}', function ($locale = null) {
//     if (isset($locale) && in_array($locale, config('app.available_locales'))) {
//         app()->setLocale($locale);
//     }

//     return view('welcome');
// });
Route::post('/locale', LocaleController::class)->name('locale.change');

Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ur'])) {
        abort(400);
    }

    // Store in session
    session()->put('locale', $locale);

    // Set cookie that persists even after browser closes
    cookie()->queue(cookie()->forever('locale', $locale));

    return redirect()->back();
})->name('language.switch');

Route::get('/current-locale', function () {
    return response()->json([
        'app_locale' => app()->getLocale(),
        'session_locale' => session()->get('locale'),
        'translations' => [
            'welcome' => __('messages.welcome'),
            'name' => __('messages.name')
        ],
        'loaded_lang_files' => app('translator')->getLoader()->namespaces()
    ]);
});

Route::get('/translation-debug', function () {
    $translator = app('translator');

    return response()->json([
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => request()->cookie('locale'),
        'loaded_locales' => ['en', 'ur'],
        'translations' => [
            'welcome' => [
                'ur' => $translator->get('messages.welcome', [], 'ur'),
                'en' => $translator->get('messages.welcome', [], 'en')
            ]
        ],
        'translator_status' => [
            'has_ur' => $translator->hasForLocale('messages.welcome', 'ur'),
            'has_en' => $translator->hasForLocale('messages.welcome', 'en')
        ]
    ]);
});

Route::get('/test-locale', function () {
    return view('test-locale');
});

Route::get('/force-urdu-test', function () {
    app()->setLocale('ur');
    session()->put('locale', 'ur');

    return response()->json([
        'forced_locale' => 'ur',
        'welcome' => __('messages.welcome'),
        'name' => __('messages.name'),
        'translation_source' => app('translator')->getLoader()->load('ur', 'messages')
    ]);
});


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'company.selected'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::prefix('admin')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users-roles', UserRoleController::class)
        ->only(['index', 'edit', 'update', 'destroy'])
        ->parameters(['users-roles' => 'user']);
});

// Route::prefix('admin')->middleware(['auth', 'role:super-admin'])->group(function () {
//     Route::get('/roles', [UserRoleController::class, 'index'])->name('admin.roles.index');
//     Route::put('/roles/{user}', [UserRoleController::class, 'update'])->name('admin.roles.update');

//     Route::get('/users', [UserRoleController::class, 'index'])->name('admin.users.index');
//     Route::get('/users/{user}/edit', [UserRoleController::class, 'edit'])->name('admin.users.edit');
//     Route::put('/users/{user}', [UserRoleController::class, 'update'])->name('admin.users.update');

//     Route::resource('roles', RoleController::class)->names('admin.roles');
// });

// Route::middleware(['auth', 'permission:create-role|edit-role|delete-role'])->group(function () {
//     Route::resource('roles', RoleController::class);
// });

// Route::middleware(['auth', 'permission:create-user|edit-user|delete-user'])->group(function () {
//     Route::resource('users', UserController::class);
// });

// Route::middleware(['auth', 'permission:view-permission|assign-permission'])->group(function () {
//     Route::resource('permissions', PermissionController::class);
// });
Route::get('/select-company', [CompanySelectionController::class, 'showForm'])->name('company.select.form');
Route::post('/select-company', [CompanySelectionController::class, 'storeSelection'])->name('company.select.store');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('main-heads', MainHeadController::class);
    Route::resource('control-heads', ControlHeadController::class);
    Route::get('control-head/get-control-head-account/{mainHead}', [ControlHeadController::class, 'getControlAccountForMainHead'])->name('get.control.head.account');
    Route::get('sub-head/get-sub-head-account/{controlHead}', [SubHeadController::class, 'getSubAccountForControlHead'])->name('get.sub.head.account');
    Route::get('sub-sub-head/get-sub-sub-head-account/{subHead}', [SubSubHeadController::class, 'getSubSubAccountForSubHead'])->name('get.sub.sub.head.account');
    Route::get('sub-sub-sub-head/get-sub-sub-sub-head-account/{subSubHead}', [SubSubSubHeadController::class, 'getSubSubSubAccountForSubSubHead'])->name('get.sub.sub.sub.head.account');
    Route::get('products/get-detail-account/{code}', [ProductController::class, 'getMaxDetailAccountCode'])->name('get.detail.account');
    Route::get('products/get-project-squareFeet/{projectId}', [ProductController::class, 'getProjectSquareFeet'])->name('get.project.squareFeet');
    Route::patch('/products/{id}/status', [ProductController::class, 'updateStatus'])->name('products.updateStatus');
    Route::patch('/bookings/{id}/status', [BookingApplicationController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::resource('sub-heads', SubHeadController::class);
    Route::resource('sub-sub-heads', SubSubHeadController::class);
    Route::resource('sub-sub-sub-heads', SubSubSubHeadController::class);
    Route::get('/detail-accounts/fetchSubSubSubHead', [DetailAccountController::class, 'fetchSubSubSubHead'])
        ->name('detail-accounts.fetchSubSubSubHead');
    Route::get('/detail-accounts/fetchSubSubHead', [DetailAccountController::class, 'fetchSubSubHead'])
        ->name('detail-accounts.fetchSubSubHead');
    Route::resource('detail-accounts', DetailAccountController::class);
    Route::get('accounts-tree', [DetailAccountController::class, 'tree'])->name('detail-accounts.tree');
    Route::resource('itemRegistration', ItemController::class);
    Route::get('possession-letter/listing', [PossessionLetterController::class, 'bookingListing'])->name('possession-letter.bookingListing');
    Route::resource('possession-letter', PossessionLetterController::class);
    Route::get('registry-order/listing', [RegistryOrderController::class, 'bookingListing'])->name('registry-order.bookingListing');
    Route::resource('registry-order', RegistryOrderController::class);
    Route::get('/get-detail-account-data', [PurchaseOrderController::class, 'getDetailAccounts'])
        ->name('get.detail.account.data.project');
    Route::get('/get-project-items/{projectId}', [PurchaseOrderController::class, 'getProjectItems'])
        ->name('purchase-order.getProjectItems');
    Route::resource('purchase-order', PurchaseOrderController::class);
    Route::patch('/purchase-order/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-order.updateStatus');
    Route::get('purchase-order/get-product-size/{id}', [PurchaseOrderController::class, 'getItemMeasurementUnitDetail'])
        ->name('purchase-order.getProductSizeDetail');
    Route::resource('grn', GRNController::class);
    Route::get('grn/generate', [GRNController::class, 'generate'])->name('grn.generate');
    Route::patch('/grn/{id}/status', [GRNController::class, 'updateStatus'])->name('grn.updateStatus');

    Route::get('purchase-invoice/generate', [PurchaseController::class, 'generate'])->name('purchase-invoice.generate');
    Route::resource('purchase-invoice', PurchaseController::class);
    Route::patch('/purchase-invoice/{id}/status', [PurchaseController::class, 'updateStatus'])->name('purchase-invoice.updateStatus');

    Route::resource('sale-invoice', SaleInvoiceController::class);
    Route::patch('/sale-invoice/{id}/status', [SaleInvoiceController::class, 'updateStatus'])->name('sale-invoice.updateStatus');

    Route::get('purchase-return/generate', [PurchaseReturnController::class, 'generate'])->name('purchase-return.generate');
    Route::resource('purchase-return', PurchaseReturnController::class);
    Route::patch('/purchase-return/{id}/status', [PurchaseReturnController::class, 'updateStatus'])->name('purchase-return.updateStatus');

    Route::get('bookingReturns/listing', [BookingReturnController::class, 'bookingListing'])->name('bookingReturns.bookingListing');
    Route::resource('bookingReturns', BookingReturnController::class);
    Route::patch('/bookingReturns/{id}/status', [BookingReturnController::class, 'updateStatus'])->name('bookingReturns.updateStatus');

    Route::resource('construction-sites', ConstructionSiteController::class);

    // Custom route for tender creation with construction site
    Route::get('tenders/create/{constructionSiteId}', [TenderController::class, 'create'])->name('tenders.create.site');
    Route::resource('tenders', TenderController::class);

    // BOQ Routes
    Route::get('boq-masters/create/{tenderId}', [BOQMasterController::class, 'create'])->name('boq-masters.create.tender');
    Route::post('boq-masters/{boqMasterId}/add-detail', [BOQMasterController::class, 'addDetail'])->name('boq-masters.addDetail');
    Route::put('boq-details/{detailId}', [BOQMasterController::class, 'updateDetail'])->name('boq-masters.updateDetail');
    Route::delete('boq-details/{detailId}', [BOQMasterController::class, 'deleteDetail'])->name('boq-masters.deleteDetail');
    Route::get('boq-masters/get-item-unit/{id}', [BOQMasterController::class, 'getItemMeasurementUnitDetail'])->name('boq-masters.getItemMeasurementUnit');
    Route::resource('boq-masters', BOQMasterController::class);

    // Work Order Routes
    Route::get('work-orders/select-boq', [WorkOrderController::class, 'selectBoq'])->name('work-orders.selectBoq');
    Route::get('boq-masters/get-by-tender/{tenderId}', [BOQMasterController::class, 'getByTender'])->name('boq-masters.getByTender');
    Route::get('work-orders/get-available-items', [WorkOrderController::class, 'getAvailableItems'])->name('work-orders.getAvailableItems');
    Route::get('work-orders/get-remaining-quantity', [WorkOrderController::class, 'getRemainingQuantity'])->name('work-orders.getRemainingQuantity');
    Route::post('work-orders/{boq_id}/get-items', [WorkOrderController::class, 'getAvailableItems'])->name('work-orders.getAvailableItemsForBOQ');
    Route::resource('work-orders', WorkOrderController::class);
    Route::get('/work-orders/get-by-tender/{tenderId}', [WorkOrderController::class, 'getByTender']);
    Route::get('work-progress/get-items/{id}', [WorkProgressController::class, 'getItems']);
    Route::resource('work-progress', WorkProgressController::class);

    // Contractor Bill Routes
    Route::get('contractor-bills/get-boq-items/{workOrderId}', [ContractorBillController::class, 'getBoqItems'])->name('contractor-bills.getBoqItems');
    Route::get('contractor-bills/get-work-orders/{tenderId}', [ContractorBillController::class, 'getWorkOrders'])->name('contractor-bills.getWorkOrders');
    Route::get('contractor-bills/get-remaining-quantity', [ContractorBillController::class, 'getRemainingQuantity'])->name('contractor-bills.getRemainingQuantity');
    Route::post('contractor-bills/{id}/verify', [ContractorBillController::class, 'verify'])->name('contractor-bills.verify');
    Route::post('contractor-bills/{id}/cancel', [ContractorBillController::class, 'cancel'])->name('contractor-bills.cancel');
    Route::get('contractor-bills/{id}/print', [ContractorBillController::class, 'print'])->name('contractor-bills.print');
    Route::get('contractor-bills/export', [ContractorBillController::class, 'export'])->name('contractor-bills.export');
    Route::resource('contractor-bills', ContractorBillController::class);

    // Contractor Payment Routes
    Route::prefix('contractor-payments')->name('contractor-payments.')->group(function () {
        Route::get('/', [ContractorPaymentController::class, 'paymentReports'])->name('index');
        Route::get('export', [ContractorPaymentController::class, 'exportPayments'])->name('export');
        Route::get('contractor/{contractorId}', [ContractorPaymentController::class, 'contractorPayments'])->name('contractor');
        Route::get('bill/{billId}', [ContractorPaymentController::class, 'showPaymentDetails'])->name('bill');
        Route::get('bill/{billId}/history', [ContractorPaymentController::class, 'paymentHistory'])->name('bill-history');
        Route::get('bill/{billId}/make-payment', [ContractorPaymentController::class, 'makePaymentForm'])->name('make-payment-form');
        Route::post('bill/{billId}/initiate', [ContractorPaymentController::class, 'initiatePayment'])->name('initiate');
        Route::post('bill/{billId}/record', [ContractorPaymentController::class, 'recordPayment'])->name('record');
        Route::delete('{paymentId}', [ContractorPaymentController::class, 'cancelPayment'])->name('cancel');
        Route::get('api/outstanding/{contractorId}', [ContractorPaymentController::class, 'getOutstandingBalance'])->name('api.outstanding');
        Route::get('api/bill-outstanding/{billId}', [ContractorPaymentController::class, 'getBillOutstanding'])->name('api.bill-outstanding');
    });

    Route::get('/partyAccount-ledger', [LedgerController::class, 'viewPartyAccountLedger'])->name('partyAccount.ledger');
    Route::get('/partyAccount-ledger-report', [LedgerController::class, 'getPartyAccountLedger'])->name('partyAccount.ledger.report');
    Route::get('/generalJournal-ledger', [LedgerController::class, 'viewGeneralJournalLedger'])->name('generalJournal.ledger');
    Route::get('/generalJournal-ledger-report', [LedgerController::class, 'getGeneralJournalLedger'])->name('generalJournal.ledger.report');
    Route::get('/bankBook', [ReportController::class, 'viewBankBook'])->name('bankBook.view');
    Route::get('/bankBook-report', [ReportController::class, 'getBankBookLedger'])->name('bank.book.report');
    Route::get('bookings/listing', [BookingApplicationController::class, 'bookingListing'])->name('bookings.bookingListing');
    Route::get('bookings/get-detail-account/{partyId}', [BookingApplicationController::class, 'getDetailAccountForParty'])->name('get.detail.account.data');
    Route::patch('/bookings/{id}/scheduleCreate', [BookingApplicationController::class, 'scheduleCreate'])->name('bookings.scheduleCreate');
    Route::patch('/bookings/{id}/clearanceLetter', [BookingApplicationController::class, 'clearanceLetter'])->name('bookings.clearanceLetter');
    Route::patch('/bookings/{id}/pre-clearanceLetter', [BookingApplicationController::class, 'preClearanceLetter'])->name('bookings.pre-clearanceLetter');
    Route::resource('bookings', BookingApplicationController::class);
    Route::post('/bookings/transfer', [BookingApplicationController::class, 'transfer'])->name('bookings.transfer');
    Route::resource('parties', RegisteredPartiesController::class);
    Route::resource('bank-payment-voucher', BankPaymentVoucherController::class);
    Route::get('bank-payment-voucher/{id}/print', [BankPaymentVoucherController::class, 'print'])->name('bank-payment-voucher.print');
    Route::get('bank-receipt-voucher/{id}/print', [BankReceiptVoucherController::class, 'print'])->name('bank-receipt-voucher.print');
    Route::get('bank-payment-voucher/get-bank-detail-account/{projectId}', [BankPaymentVoucherController::class, 'getBankAndDetailAccount'])->name('get.bank.detail.account');
    Route::resource('bank-receipt-voucher', BankReceiptVoucherController::class);
    Route::get('bank-receipt-voucher/get-bank-detail-account/{projectId}', [BankReceiptVoucherController::class, 'getBankAndDetailAccount'])->name('get.brv.bank.detail.account');
    Route::resource('cash-payment-voucher', CashPaymentVoucherController::class);
    Route::get('cash-payment-voucher/get-cash-detail-account/{projectId}', [CashPaymentVoucherController::class, 'getCashAccountsAndDetailAccount'])->name('get.cash.detail.account');
    Route::resource('cash-receipt-voucher', CashReceiptVoucherController::class);
    Route::get('cash-receipt-voucher/get-cash-detail-account/{projectId}', [CashReceiptVoucherController::class, 'getCashAccountsAndDetailAccount'])->name('get.crv.cash.detail.account');

    Route::resource('jv-voucher', JournalVoucherController::class);

    Route::resource('client-invoices', ClientInvoiceController::class);
    Route::post('client-invoices/{invoice}/verify', [ClientInvoiceController::class, 'verify'])->name('client-invoices.verify');
    Route::post('client-invoices/{invoice}/cancel', [ClientInvoiceController::class, 'cancel'])->name('client-invoices.cancel');
    Route::get('client-invoices/{invoice}/print', [ClientInvoiceController::class, 'print'])->name('client-invoices.print');
    Route::get('client-invoices/api/clients/{tenderId}', [ClientInvoiceController::class, 'getClients'])->name('client-invoices.getClients');

    Route::get('/clients/select2', [ClientInvoiceController::class, 'select2'])
        ->name('clients.select2');


    // Client Invoice Receipt Tracking Routes
    Route::prefix('client-invoices/{invoice}/receipts')->name('client-invoices.receipts.')->group(function () {
        Route::get('/', [ClientInvoiceReceiptController::class, 'show'])->name('show');
        Route::get('/create', [ClientInvoiceReceiptController::class, 'create'])->name('create');
        Route::post('/', [ClientInvoiceReceiptController::class, 'store'])->name('store');
        Route::delete('/{receipt}', [ClientInvoiceReceiptController::class, 'destroy'])->name('destroy');
        Route::get('/api/available-vouchers', [ClientInvoiceReceiptController::class, 'getAvailableVouchers'])->name('api.available-vouchers');
    });

    // Receipt Reports
    Route::prefix('receipts')->name('receipts.')->group(function () {
        Route::get('/outstanding-receivables', [ClientInvoiceReceiptController::class, 'outstandingReceivables'])->name('outstanding');
        Route::get('/history', [ClientInvoiceReceiptController::class, 'history'])->name('history');
    });

    Route::get('/reports/stock-report', [ReportController::class, 'stockReport'])
        ->name('reports.stock-report');
    Route::get(
        '/reports/available-plots/filter',
        [ReportController::class, 'availablePlotsReportFilter']
    )->name('available-plots.filter');

    Route::get(
        '/reports/available-plots',
        [ReportController::class, 'availablePlotsReport']
    )->name('available-plots.report');

    Route::get(
        '/exective-reports/direct-products/filter',
        [ReportController::class, 'directProductProjectReportFilter']
    )->name('exective-reports.direct-products.filter');

    Route::get(
        '/exective-reports/direct-products',
        [ReportController::class, 'directProductProjectReport']
    )->name('exective-reports.direct-products.report');

    Route::get('/reports/stock-report/filter', [ReportController::class, 'stockReportFilter'])->name('reports.stock-report.filter');

    Route::resource('products', ProductController::class);
    Route::resource('unitRegistration', UnitRegistrationController::class);
    Route::get('unitRegistration/get-project-information/{projectId}', [UnitRegistrationController::class, 'getProjectInformation'])->name('get.project.information');
    Route::get('unitRegistration/get-product-information/{productId}', [UnitRegistrationController::class, 'getProductInformation'])->name('get.product.information');
    Route::resource('companies', CompanyController::class);
    Route::resource('audit-logs', AuditLogController::class);
    Route::resource('users', UserController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('cities', CityController::class);
    Route::resource('residentials', ResidentialController::class);
    Route::resource('banks', BankController::class);
    Route::resource('relations', RelationController::class);
    Route::resource('periods', SchedulePeriodController::class);
    Route::resource('casts', CastController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('units', UnitController::class);
    Route::resource('tehsils', TehsilController::class);
    Route::resource('areas', controller: AreaController::class);
    Route::get('/get-tehsils/{city_id}', action: [AreaController::class, 'getTehsilsByCity']);
    Route::resource('occupation-types', OccupationTypeController::class);
    Route::resource('phase-types', PhaseDivisionTypeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('road-categories', RoadCategoryController::class);
    Route::resource('road-specifications', RoadSpecificationController::class);
    Route::resource('schedule-types', ScheduleTypeController::class);
    Route::resource('dealers', DealerController::class);

    // Construction Module Routes
    Route::prefix('construction')->name('construction.')->group(function () {
        Route::resource('construction-sites', ConstructionSiteController::class);
        Route::resource('tenders', TenderController::class);
        Route::resource('boq-masters', BOQMasterController::class);
        Route::resource('work-orders', WorkOrderController::class);
        Route::get('work-orders/get-available-items', [WorkOrderController::class, 'getAvailableItems'])->name('work-orders.get-available-items');
        Route::get('work-orders/get-remaining-quantity', [WorkOrderController::class, 'getRemainingQuantity'])->name('work-orders.get-remaining-quantity');
        Route::get('work-orders/get-by-tender/{tenderId}', [WorkOrderController::class, 'getByTender'])->name('work-orders.get-by-tender');
        Route::resource('work-progress', WorkProgressController::class);
        Route::resource('contractor-bills', ContractorBillController::class);
    });

    //Payroll routes
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/qualifications', [QualificationController::class, 'index'])->name('qualifications');
    });
    Route::resource('registry-types', RegistryTypeController::class);
    Route::resource('land-registrations', LandRegistrationController::class);
    Route::post('land-registrations/calculate', [LandRegistrationController::class, 'calculate'])->name('land-registrations.calculate');
    Route::get('/test-performance', function () {
        $start = microtime(true);
        $count = \App\Models\LandRegistration::count();
        $time = round((microtime(true) - $start) * 1000, 2);

        $start2 = microtime(true);
        $paginated = \App\Models\LandRegistration::paginate(25);
        $time2 = round((microtime(true) - $start2) * 1000, 2);

        return response()->json([
            'total_records' => $count,
            'count_time_ms' => $time,
            'pagination_time_ms' => $time2,
            'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB'
        ]);
    });
});
Route::middleware(['auth'])->prefix('reports')->group(function () {
    Route::get('/recovery-sheet', [ReportController::class, 'viewRecoverySheet'])
        ->name('reports.recovery.sheet.view');

    Route::get('/recovery-sheet/report', [ReportController::class, 'getRecoveryReport'])
        ->name('reports.recovery.sheet.report');

    Route::get('/bill-aging', [ReportController::class, 'viewBillAgingReport'])
        ->name('reports.bill.aging.view');

    Route::get('/bill-aging/report', [ReportController::class, 'getBillAgingReport'])
        ->name('reports.bill.aging.report');

    Route::get('/trial-balance', [ReportController::class, 'viewTrialBalance'])
        ->name('reports.trial.balance.view');

    Route::get('/trial-balance/report', [ReportController::class, 'getTrialBalance'])
        ->name('reports.trial.balance.report');

    Route::get('/balance-sheet', [ReportController::class, 'viewBalanceSheet'])
        ->name('reports.balance.sheet.view');

    Route::get('/balance-sheet/report', [ReportController::class, 'getBalanceSheet'])
        ->name('reports.balance.sheet.report');
});

Route::resource('lands', LandController::class);
Route::resource('land-transfers', LandTransferController::class);
Route::get('lands/{land}/transfer', [LandTransferController::class, 'create'])
    ->name('lands.transfer.create');
// Route::get('/land-balance-report', [LandReportController::class, 'landBalanceReport']);
// Route::get('/land-balance-report/pdf', [LandReportController::class, 'downloadPdfReport']);
// Route::prefix('land-reports')->group(function () {
//     Route::get('/balance-report', [LandReportController::class, 'balanceReport'])->name('land-report.balance');
//     Route::get('/generate', [LandReportController::class, 'generateReport'])->name('land-report.generate');
// });
Route::prefix('land-reports')->group(function () {
    Route::get('/area-summary', [LandReportController::class, 'areaSummaryReport'])->name('land-report.area-summary');
    Route::post('/area-summary', [LandReportController::class, 'areaSummaryReport']);
    Route::get('/export-pdf', [LandReportController::class, 'exportPdf'])->name('land-report.export-pdf');
});

require __DIR__ . '/auth.php';
