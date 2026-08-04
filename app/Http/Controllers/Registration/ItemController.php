<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreItemRequest;
use App\Models\ControlHead;
use App\Models\Item;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Models\SubSubHead;
use App\Models\SubSubSubHead;
use App\Models\Unit;
use App\Services\CommonService;
use App\Services\ItemRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    protected $itemRegistrationService;

    public function __construct(ItemRegistrationService $itemRegistrationService)
    {
        $this->itemRegistrationService = $itemRegistrationService;
    }

    private function getItemMasterData()
    {
        return [
            'mainHeads' => Cache::remember('main_heads_data', 3600, fn() =>
            MainHead::select('id', 'name_en', 'name_ur')->get()),

            'searchControlHeads' => Cache::remember('control_heads_data', 3600, fn() =>
            ControlHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubHeads' => Cache::remember('sub_heads_data', 3600, fn() =>
            SubHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubSubHeads' => Cache::remember('sub_sub_heads_data', 3600, fn() =>
            SubSubHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubSubSubHeads' => Cache::remember('sub_sub_sub_heads_data', 3600, fn() =>
            SubSubSubHead::select('id', 'name_en', 'name_ur')->get()),
        ];
    }

    private function getUnitsData()
    {
        return [
            'units' => Cache::remember('units_data', 3600, fn() =>
            Unit::select('id', 'name_en', 'name_ur')->get()),
        ];
    }

    /**
     * Display a listing of Items.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $search = $request->input('search');

        $itemsListing = Item::with([
            'mainHead:id,name_en,name_ur',
            'controlHead:id,name_en,name_ur',
            'subHead:id,name_en,name_ur',
            'subSubHead:id,name_en,name_ur',
            'subSubSubHead:id,name_en,name_ur',
            'measurementUnit:id,name_en,name_ur',
        ])
            ->search($search, $filters)
            ->latest()
            ->paginate(10);

        return view(
            'registration.items.index',
            array_merge(
                [
                    'itemsListing' => $itemsListing,
                    'search' => $search,
                ],
                $this->getItemMasterData()
            )
        );
    }

    /**
     * Show the form for creating a new Item.
     */
    public function create()
    {
        return view('registration.items.create',
            array_merge(
                $this->getItemMasterData(),
                $this->getUnitsData()
            )
        );
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(StoreItemRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $itemService = app(ItemRegistrationService::class);

            // 2️⃣ Normalize sub_sub_sub_head_id to an array
            $subSubSubHeadIds = is_array($data['sub_sub_sub_head_id'])
                ? $data['sub_sub_sub_head_id']
                : [$data['sub_sub_sub_head_id']];

            $createdItems = [];

            // 3️⃣ Loop through each sub_sub_sub_head_id
            foreach ($subSubSubHeadIds as $subSubSubHeadId) {
                $itemData = $data;
                $itemData['sub_sub_sub_head_id'] = $subSubSubHeadId;

                // 🔹 Fetch related project info
                $subSubSubHead = SubSubSubHead::with('projects')->find($subSubSubHeadId);
                if ($subSubSubHead && $subSubSubHead->projects) {
                    $project = $subSubSubHead->projects;
                    $projectNameEN = $project->name_en ?? null;
                    $projectNameUR = $project->name_ur ?? null;

                    // Combine base item name with project name
                    $itemData['name_en'] = trim($projectNameEN . ' ' . $itemData['name_en'] ?? '');
                    $itemData['name_ur'] = trim($projectNameUR . ' ' . $data['name_ur'] ?? '');
                }

                // 4️⃣ Create the Item
                $item = $itemService->create($itemData);

                // 5️⃣ Create the related Detail Account
                $detailAccount = $itemService->createDetailAccount($itemData);

                // 6️⃣ Create the Product data
                $product = $itemService->createProductData($itemData);

                $createdItems[] = [
                    'item' => $item,
                    'detail_account' => $detailAccount,
                    'product' => $product,
                ];
            }

            DB::commit();

            return redirect()
                ->route('itemRegistration.index')
                ->with('success', __('messages.record-saved'));
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Item registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        try {
            $item = $this->itemRegistrationService->getById($id);

            $controlHeads = ControlHead::where('main_head_id', $item->main_head_id)->get();

            $subHeads = SubHead::where('control_head_id', $item->control_head_id)->get();

            $subSubHeads = SubSubHead::where('sub_head_id', $item->sub_head_id)->get();

            $subSubSubHeads = SubSubSubHead::where('sub_sub_head_id', $item->sub_sub_head_id)->get();

            return view(
                'registration.items.edit',
                array_merge(
                    [
                        'item' => $item,
                        'controlHeads' => $controlHeads,
                        'subHeads' => $subHeads,
                        'subSubHeads' => $subSubHeads,
                        'subSubSubHeads' => $subSubSubHeads,
                    ],
                    [
                        'mainHeads' => Cache::remember(
                            'main_heads_data',
                            3600,
                            fn() => MainHead::select('id', 'name_en', 'name_ur')->get()
                        ),
                    ],
                    $this->getUnitsData()
                )
            );
        } catch (\Exception $e) {
            return redirect()
                ->route('itemRegistration.index')
                ->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified item in storage.
     */

    public function update(StoreItemRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            // Update related records in proper order
            $this->itemRegistrationService->updateProductData($data, $id);
            $this->itemRegistrationService->updateDetailAccountData($data, $id);
            $this->itemRegistrationService->update($id, $data, $request->file('item_image'));

            DB::commit();

            return redirect()
                ->route('itemRegistration.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: log the full error details for debugging
            Log::error('Item update failed: ' . $e->getMessage(), [
                'item_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.unexpected-error'));
        }
    }


    /**
     * Display the specified item details.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Item $itemRegistration)
    {
        try {
            return view('registration.items.show', compact('itemRegistration'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy($id)
    {
        try {
            $this->itemRegistrationService->delete($id);
            return redirect()->route('itemRegistration.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('itemRegistration.index')->with('error', __('messages.unexpected-error'));
        }
    }
}
