<?php

namespace App\Http\Controllers\PurchaseModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseModule\StorePlotPurchaseRequestphp;
use App\Http\Requests\PurchaseModule\UpdatePlotPurchaseRequestphp;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\PlotPurchaseDetail;
use App\Models\PlotPurchaseMaster;
use App\Models\Product;
use App\Services\PlotPurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PlotPurchaseController extends Controller
{
    protected $plotPurchaseService;

    public function __construct(PlotPurchaseService $plotPurchaseService)
    {
        $this->plotPurchaseService = $plotPurchaseService;
    }

    private function getMasterData()
    {
        return [
            'projects' => Cache::remember('projects_data', 3600, fn() =>
            \App\Models\Project::select('id', 'name_en', 'name_ur')->get()),

            'searchParties' => Cache::remember('parties_data', 3600, fn() =>
            \App\Models\Party::with('cast')->select('id', 'name_en', 'name_ur', 'cnic_no', 'contact_number_1', 'cast_id')->get()),

            'detailAccounts' => Cache::remember('detail_accounts_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->get()),

            'products' => Cache::remember('products_data', 3600, fn() =>
            Product::select('id', 'name_en', 'name_ur')->get()),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $plotPurchaseListing = PlotPurchaseMaster::with('party', 'detailAccount', 'project')->search($filters)->latest()->paginate(10);

        return view('purchase-module.plot-purchase.index', array_merge(
            [
                'plotPurchaseListing' => $plotPurchaseListing
            ],
            $this->getMasterData()
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $purchaseId = PlotPurchaseMaster::max('id');
        $maxId = $purchaseId ? $purchaseId + 1 : 1;

        return view('purchase-module.plot-purchase.create', array_merge(
            [
                'maxId' => $maxId
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlotPurchaseRequestphp $request)
    {
        try {
            $this->plotPurchaseService->store($request->validated());

            return redirect()->route('plot-purchase-invoice.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $purchaseMaster = $this->plotPurchaseService->getById($id);
            $purchaseDetails = PlotPurchaseDetail::where('plot_purchase_master_id', $id)->get();

            return view('purchase-module.plot-purchase.edit', array_merge(
                [
                    'purchaseMaster' => $purchaseMaster,
                    'purchaseDetails' => $purchaseDetails
                ],
                $this->getMasterData()
            ));
        } catch (\Exception $e) {
            return redirect()->route('plot-purchase-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlotPurchaseRequestphp $request, $id)
    {
        // try {
        $purchaseMaster = PlotPurchaseMaster::findOrFail($id);
        $this->plotPurchaseService->update($request->validated(), $purchaseMaster);

        return redirect()
            ->route('plot-purchase-invoice.index')
            ->with('success', __('messages.record-updated'));
        // } catch (\Throwable $e) {
        //     return back()
        //         ->withInput()
        //         ->withErrors(['error' => 'Something went wrong while updating the purchase order.']);
        // }
    }

    /**
     * Display the specified Purchase order details with related Bank Accounts.
     *
     * @param  \App\Models\PurchaseMaster  $purchaseMaster
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $purchaseMaster = PlotPurchaseMaster::where('id', $id)->first();
            $purchaseInvoiceDetails = $this->plotPurchaseService->getPurchaseDetails($purchaseMaster->id);

            return view('purchase-module.plot-purchase.show', array_merge(
                [
                    'purchaseMaster' => $purchaseMaster,
                    'purchaseInvoiceDetails' => $purchaseInvoiceDetails
                ],
                $this->getMasterData()
            ));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->plotPurchaseService->delete($id);
            return redirect()->route('plot-purchase-invoice.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('plot-purchase-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }
    public function generate()
    {
        return view('purchase-module.purchase.generate');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);
        // DB::beginTransaction();
        // try {

        $purchaseInvoiceStatus = PlotPurchaseMaster::lockForUpdate()->findOrFail($id);

        // Prevent duplicate processing
        if ($purchaseInvoiceStatus->status === 'Verified') {
            DB::rollBack();
            return redirect()->route('plot-purchase-invoice.index')
                ->with('info', 'This Invoice already verified.');
        }

        // Update status
        $purchaseInvoiceStatus->status = $request->status;
        $purchaseInvoiceStatus->save();

        // Fetch related payments
        $purchaseDetails = PlotPurchaseDetail::where('plot_purchase_master_id', $purchaseInvoiceStatus->id)->get();
        $documentNo = 'P-P-I' . '-' . $purchaseInvoiceStatus->id;
        // Only create ledger entries if they don’t already exist
        $ledgerExists = AccountLedger::where('invoice_id', $purchaseInvoiceStatus->id)->where('document_number', $documentNo)->exists();
        $journalExists = GeneralJournal::where('invoice_id', $purchaseInvoiceStatus->id)->where('document_number', $documentNo)->exists();

        if (!$ledgerExists && !$journalExists) {
            $this->plotPurchaseService->createLedgerEntry($purchaseInvoiceStatus, $purchaseDetails);
        }

        DB::commit();

        return redirect()->route('plot-purchase-invoice.index')
            ->with('success', __('messages.record-updated'));
        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     Log::error(' Purchase Invoice verification failed', [
        //         'Purchase_invoice_id' => $id,
        //         'error' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString(),
        //     ]);

        //     return redirect()->route('plot-purchase-invoice.index')
        //         ->with('error', 'An error occurred while verifying the booking. Please try again.');
        // }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->plotPurchaseService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['status' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['status' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => []], 500);
        }
    }

    public function getDetailAccounts(Request $request)
    {
        $partyId = $request->party_id;
        $projectId = $request->project_id;

        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $query = DetailAccount::query()->where('main_head_id', '2');

        if (!empty($partyId)) {
            $query->where('party_id', $partyId);
        }

        if (!empty($projectId)) {
            $query->whereHas('subSubSubHead', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }

        $detailAccounts = $query->pluck($field, 'id');

        return response()->json([
            'status' => 'success',
            'data' => $detailAccounts
        ]);
    }

    public function getProjectProducts($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $items = Product::whereHas('subSubSubHead', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->pluck($field, 'id', 'total_marla', 'amount_in_pkr', 'total_amount');

        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }
}
