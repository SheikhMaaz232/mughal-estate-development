<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\RegistryOrderRequest;
use App\Models\BookingApplication;
use App\Models\DetailAccount;
use App\Models\Party;
use App\Models\Product;
use App\Models\RegistryOrder;
use App\Models\SubSubSubHead;
use App\Services\RegistryOrderService;
use Illuminate\Http\Request;

class RegistryOrderController extends Controller
{
    protected $registryOrderService;

    public function __construct(RegistryOrderService $registryOrderService)
    {
        $this->registryOrderService = $registryOrderService;
    }

    /**
     * Display a listing of Possession Letters.
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $registryOrdersListing = RegistryOrder::with('party', 'booking.project', 'booking.product')->search($search)->latest()->paginate(10)->appends(request()->input());

        return view('registration.registry-order.index', compact('registryOrdersListing', 'request'));
    }

    public function bookingListing(Request $request)
    {
        $search = $request->all();
        $bookings = BookingApplication::where('status', 'Verified')->search($search)->latest()->paginate(10);

        return view('registration.registry-order.verifiedBookings', compact('bookings'));
    }

    /**
     * Show the form for creating a new Possession Letters.
     */
    public function create(Request $request)
    {
        $booking = BookingApplication::with('party', 'project', 'product', 'registryOrder')->findOrFail($request->id);
        $product = Product::where('id', $booking->product_id)->first();
        $projectSubSubSubHeads = SubSubSubHead::select('id', 'name_en', 'name_ur')->where('project_id', $product->project_id)->pluck('id');
        $registryAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 5)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        if ($booking->registryOrder) {
            return redirect()
                ->back()
                ->with('error', __('messages.registry_order_exists'));
        }
        return view('registration.registry-order.create', compact('booking', 'registryAccounts'));
    }

    /**
     * Store a newly created Possession Letters in storage.
     */
    public function store(RegistryOrderRequest $request)
    {
        try {
            $data = $request->all();

            app(RegistryOrderService::class)->create($data);

            return redirect()->route('registry-order.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified Sub-Sub-Head.
     */
    public function edit($id)
    {
        try {
            $registryOrder = $this->registryOrderService->getById($id);
            $product = Product::where('id', $registryOrder->booking->product_id)->first();
            $projectSubSubSubHeads = SubSubSubHead::select('id', 'name_en', 'name_ur')->where('project_id', $product->project_id)->pluck('id');
            $registryAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 5)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();

            return view('registration.registry-order.edit', compact('registryOrder', 'registryAccounts'));
        } catch (\Exception $e) {
            return redirect()->route('registry-order.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */
    public function update(RegistryOrderRequest $request, $id)
    {
        try {
            $data = $request->all();
            $this->registryOrderService->update($data, $id);

            return redirect()->route('registry-order.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified Party details with related Bank Accounts.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(RegistryOrder $registryOrder)
    {
        try {
            return view('registration.registry-order.show', compact('registryOrder'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified Sub-Sub-Head from storage.
     */
    public function destroy($id)
    {
        try {
            $this->registryOrderService->delete($id);
            return redirect()->route('registry-order.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('registry-order.index')->with('error', __('messages.unexpected-error'));
        }
    }
}
