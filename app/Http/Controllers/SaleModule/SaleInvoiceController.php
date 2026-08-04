<?php

namespace App\Http\Controllers\SaleModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleModule\SaleInvoiceRequest;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\Item;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceDetail;
use App\Services\SaleInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleInvoiceController extends Controller
{
  protected $saleService;

    public function __construct(SaleInvoiceService $saleService)
    {
        $this->saleService = $saleService;
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

            'items' => Cache::remember('items_data', 3600, fn() =>
            Item::select('id', 'name_en', 'name_ur', 'measurement_unit_id')->get()),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $saleInvoicesListing = SaleInvoice::with('party', 'detailAccount', 'project')->search($filters)->latest()->paginate(10);

        return view('sale-module.sale.index', array_merge(
            [
                'saleInvoicesListing' => $saleInvoicesListing
            ],
            $this->getMasterData()
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $saleInvoiceId = SaleInvoice::max('id');
        $maxId = $saleInvoiceId ? $saleInvoiceId + 1 : 1;

        return view('sale-module.sale.create', array_merge(
            [
                'maxId' => $maxId
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleInvoiceRequest $request)
    {
        try {
            $this->saleService->store($request->validated());

            return redirect()->route('sale-invoice.index')->with('success', __('messages.record-saved'));
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
            $saleMaster = $this->saleService->getById($id);
            $saleDetails = SaleInvoiceDetail::where('sale_invoice_master_id', $id)->get();

            return view('sale-module.sale.edit', array_merge(
                [
                    'saleMaster' => $saleMaster,
                    'saleDetails' => $saleDetails
                ],
                $this->getMasterData()
            ));
        } catch (\Exception $e) {
            return redirect()->route('sale-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleInvoiceRequest $request, $id)
    {

        try {
            $saleMaster = SaleInvoice::findOrFail($id);
            $this->saleService->update($request->validated(), $saleMaster);

            return redirect()
                ->route('sale-invoice.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while updating the sale invoice.']);
        }
    }

    /**
     * Display the specified Sale invoice details with related Bank Accounts.
     *
     * @param \App\Models\SaleInvoice  $saleInvoice
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $saleInvoice = SaleInvoice::where('id', $id)->first();
            $saleInvoiceDetails = $this->saleService->getSaleInvoiceDetails($saleInvoice->id);

            return view('sale-module.sale.show', array_merge(
                [
                    'saleInvoice' => $saleInvoice,
                    'saleInvoiceDetails' => $saleInvoiceDetails
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
            $this->saleService->delete($id);
            return redirect()->route('sale-invoice.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('sale-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }
    public function generate()
    {
        return view('sale-module.sale.generate');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);
        DB::beginTransaction();
        try {
            $saleInvoiceStatus = SaleInvoice::lockForUpdate()->findOrFail($id);

            // Prevent duplicate processing
            if ($saleInvoiceStatus->status === 'Verified') {
                DB::rollBack();
                return redirect()->route('sale-invoice.index')
                    ->with('info', 'This Invoice already verified.');
            }

            // Update status
            $saleInvoiceStatus->status = $request->status;
            $saleInvoiceStatus->save();

            // Fetch related payments
            $saleDetails = SaleInvoiceDetail::where('sale_invoice_master_id', $saleInvoiceStatus->id)->get();
            $documentNo = 'S-I' . '-' . $saleInvoiceStatus->id;
            // Only create ledger entries if they don’t already exist
            $ledgerExists = AccountLedger::where('invoice_id', $saleInvoiceStatus->id)->where('document_number', $documentNo)->exists();
            $journalExists = GeneralJournal::where('invoice_id', $saleInvoiceStatus->id)->where('document_number', $documentNo)->exists();

            if (!$ledgerExists && !$journalExists) {
                $this->saleService->createLedgerEntry($saleInvoiceStatus, $saleDetails);
            }

            DB::commit();

            return redirect()->route('sale-invoice.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(' Sale Invoice verification failed', [
                'sale_invoice_master_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('sale-invoice.index')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->saleService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['unit' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['unit' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['unit' => 'fail', 'data' => []]);
        }
    }
}
