<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientInvoiceRequest;
use App\Http\Requests\UpdateClientInvoiceRequest;
use App\Models\ClientInvoice;
use App\Models\DetailAccount;
use App\Models\Tender;
use App\Services\ClientInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientInvoiceController extends Controller
{
    protected $clientInvoiceService;

    public function __construct(ClientInvoiceService $clientInvoiceService)
    {
        $this->clientInvoiceService = $clientInvoiceService;
    }

    /**
     * Display a listing of client invoices
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'tender_id' => $request->input('tender_id'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'per_page' => $request->input('per_page', 15),
        ];

        $invoices = $this->clientInvoiceService->getAll($filters);
        $tenders = Tender::select('id', 'title_en', 'title_ur')->get();
        $statuses = ['draft', 'verified', 'partial_received', 'received', 'cancelled'];

        return view('registration.client-invoices.index', compact('invoices', 'tenders', 'statuses'));
    }

    /**
     * Show the form for creating a new client invoice
     */
    public function create(Request $request)
    {
        $tenderId = $request->get('tender_id');
        $nextId = ClientInvoice::max('id') + 1;
        $tenders = Tender::select('id', 'title_en', 'title_ur')->get();
        $clients = DetailAccount::select('id', 'name_en', 'name_ur', 'party_id')->get();

        return view('registration.client-invoices.create', compact('tenders', 'nextId', 'clients', 'tenderId'));
    }

    /**
     * Store a newly created client invoice
     */
    public function store(StoreClientInvoiceRequest $request)
    {
        // try {
        $invoice = $this->clientInvoiceService->store($request->validated());

        return redirect()
            ->route('client-invoices.show', $invoice)
            ->with('success', __('messages.invoice-created-successfully'));
        // } catch (\Exception $e) {
        //     return back()
        //         ->withError(__('messages.error-creating-invoice') . ': ' . $e->getMessage())
        //         ->withInput();
        // }
    }

    /**
     * Display the specified client invoice
     */
    public function show($invoice)
    {
        $invoiceData = ClientInvoice::findOrFail($invoice);
        $invoice = $this->clientInvoiceService->getInvoiceWithReceipts($invoiceData->id);

        return view('registration.client-invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified client invoice
     */
    public function edit($id)
    {
        $invoice = ClientInvoice::findOrFail($id);
        if (!$invoice->canBeEdited()) {
            return redirect()
                ->route('client-invoices.show', $invoice)
                ->withError(__('messages.invoice-cannot-be-edited'));
        }

        $tenders = Tender::select('id', 'title_en', 'title_ur')->get();
        $clients = DetailAccount::select('id', 'name_en', 'name_ur', 'party_id')->get();

        return view('registration.client-invoices.edit', compact('invoice', 'tenders', 'clients'));
    }

    /**
     * Update the specified client invoice
     */
    public function update(UpdateClientInvoiceRequest $request, $id)
    {
        try {
            $clientInvoice = ClientInvoice::findOrFail($id);
            $invoice = $this->clientInvoiceService->update($clientInvoice, $request->validated());

            return redirect()
                ->route('client-invoices.show', $invoice)
                ->with('success', __('messages.invoice-updated-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-updating-invoice') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified client invoice
     */
    public function destroy(ClientInvoice $invoice)
    {
        try {
            $this->clientInvoiceService->delete($invoice);

            return redirect()
                ->route('client-invoices.index')
                ->with('success', __('messages.invoice-deleted-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-deleting-invoice') . ': ' . $e->getMessage());
        }
    }

    /**
     * Verify invoice and create JV
     */
    public function verify($invoice)
    {
        try {
            $invoice = $this->clientInvoiceService->verify($invoice);

            return redirect()
                ->route('client-invoices.show', $invoice)
                ->with('success', __('messages.invoice-verified-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-verifying-invoice') . ': ' . $e->getMessage());
        }
    }

    /**
     * Cancel invoice
     */
    public function cancel(ClientInvoice $invoice)
    {
        try {
            $invoice = $this->clientInvoiceService->cancel($invoice);

            return redirect()
                ->route('client-invoices.show', $invoice)
                ->with('success', __('messages.invoice-cancelled-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-cancelling-invoice') . ': ' . $e->getMessage());
        }
    }

    /**
     * Print invoice
     */
    public function print(ClientInvoice $invoice)
    {
        $invoice = $this->clientInvoiceService->getInvoiceForPrint($invoice->id);

        return view('registration.client-invoices.print', compact('invoice'));
    }

    /**
     * Get clients for a tender (API)
     */
    public function getClients($tenderId)
    {
        try {
            $clients = $this->clientInvoiceService->getAvailableClients($tenderId);

            return response()->json(['success' => true, 'data' => $clients]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get invoice data for DataTables
     */
    public function datatable(Request $request)
    {
        $filters = [
            'search' => $request->input('search.value'),
            'status' => $request->input('columns.4.search.value'),
            'tender_id' => $request->input('columns.1.search.value'),
            'from_date' => $request->input('columns.2.search.value'),
            'to_date' => $request->input('columns.2.search.value'),
        ];

        $invoices = $this->clientInvoiceService->getAll($filters);

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $invoices->total(),
            'recordsFiltered' => $invoices->total(),
            'data' => $invoices->items(),
        ]);
    }

    public function select2(Request $request)
    {
        $field = app()->getLocale() === 'ur'
            ? 'name_ur'
            : 'name_en';

        $clients = DetailAccount::query()
            ->when($request->search, function ($query) use ($request, $field) {
                $query->where($field, 'like', '%' . $request->search . '%');
            })
            ->paginate(20);

        return response()->json([
            'results' => $clients->map(function ($client) use ($field) {
                return [
                    'id' => $client->id,
                    'text' => $client->$field,
                ];
            }),
            'pagination' => [
                'more' => $clients->hasMorePages(),
            ],
        ]);
    }
}
