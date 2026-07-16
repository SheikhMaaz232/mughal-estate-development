<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Product;
use App\Models\DetailAccount;
use App\Models\SubSubSubHead;
use Illuminate\Support\Facades\Storage;

class ItemRegistrationService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return Item::findOrFail($id);
    }

    public function create(array $data): Item
    {
        // Handle image uploads directly from $data if present
        if (!empty($data['item_image']) && $data['item_image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['item_image'] = $this->commonService->uploadImage($data['item_image'], 'item_images');
        }

        // Create the Item
        $item = Item::create($data);

        return $item;
    }

    public function createDetailAccount(array $data)
    {
        $project = SubSubSubHead::find($data['sub_sub_sub_head_id'])?->project_id;
        $detailAccountData = [
            'main_head_id'        => $data['main_head_id'] ?? null,
            'control_head_id'     => $data['control_head_id'] ?? null,
            'sub_head_id'         => $data['sub_head_id'] ?? null,
            'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
            'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
            'party_id'            => null,
            'project_id'          => $project ?? null,
            'name_en'             => $data['name_en'] ?? null,
            'name_ur'             => $data['name_ur'] ?? null,
        ];
        // Save
        return DetailAccount::create($detailAccountData);
    }

    public function createProductData(array $data)
    {
        // Step 1: Generate a code prefix based on IDs
        $prefix = 'Pro-' . implode('', [
            $data['main_head_id'],
            $data['control_head_id'],
            $data['sub_head_id'],
            $data['sub_sub_head_id'],
            $data['sub_sub_sub_head_id'],
        ]);

        // Step 2: Generate a unique code safely
        for ($i = 0; $i < 5; $i++) {
            $nextId = (Product::lockForUpdate()->max('id') ?? 0) + 1;
            $code = $prefix . $nextId;

            if (!Product::where('code', $code)->exists()) {
                $data['code'] = $code;
                break;
            }

            usleep(100000); // small delay to reduce collision risk
        }

        if (empty($data['code'])) {
            throw new \RuntimeException('Failed to generate unique code after multiple attempts.');
        }

        // Step 3: Determine project_id if not provided
        $project = SubSubSubHead::find($data['sub_sub_sub_head_id'])?->project_id;

        // Step 4: Build product data
        $productData = [
            'main_head_id'        => $data['main_head_id'] ?? null,
            'control_head_id'     => $data['control_head_id'] ?? null,
            'sub_head_id'         => $data['sub_head_id'] ?? null,
            'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
            'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
            'company_id'          => null,
            'project_id'          => $project ?? null,
            'road_id'             => null,
            'front_id'            => null,
            'amount_in_pkr'       => null,
            'total_amount'        => null,
            'unit_no'             => null,
            'block'               => null,
            'code'                => $data['code'],
            'kanal'               => null,
            'marla'               => null,
            'front_width'         => null,
            'length'              => null,
            'front_width2'        => null,
            'length2'             => null,
            'total_marla'         => null,
            'square_feet'         => null,
            'total_square_feet'   => null,
            'status'              => 'Unverified',
            'image'               => null,
            'name_en'             => $data['name_en'] ?? null,
            'name_ur'             => $data['name_ur'] ?? null,
            'termsAndConditions'  => null,
            'type'                => 'item',
        ];

        // Step 5: Create product within the existing transaction context
        return Product::create($productData);
    }

    public function update(int $id, array $data, $itemImage = null): Item
    {
        $item = Item::findOrFail($id);

        if ($itemImage) {
            // Optionally delete the old image
            if ($item->item_image && Storage::disk('public')->exists($item->item_image)) {
                Storage::disk('public')->delete($item->item_image);
            }

            // Upload and store new image
            $data['item_image'] = $this->commonService->uploadImage($itemImage, 'item_images');
        }

        $item->update($data);

        return $item;
    }

    public function updateProductData(array $data, int $id)
    {
        // Step 1: Find the related item
        $item = Item::select('name_en', 'name_ur', 'sub_sub_sub_head_id')
            ->findOrFail($id);

        // Step 2: Determine project_id
        $project = SubSubSubHead::find($item->sub_sub_sub_head_id)?->project_id;

        // Step 3: Find the related product
        $product = Product::select('status')->where('project_id', $project)
            ->where('name_en', $item->name_en)
            ->where('name_ur', $item->name_ur)
            ->first();

        // Step 4: Build a clean code prefix
        $prefix = 'Pro-' . implode('', [
            $data['main_head_id'],
            $data['control_head_id'],
            $data['sub_head_id'],
            $data['sub_sub_head_id'],
            $data['sub_sub_sub_head_id'],
        ]);

        if ($product) {
            $code = $prefix . $id;
        } else {
            // Attempt up to 5 times to generate a unique code
            for ($i = 0; $i < 5; $i++) {
                $nextId = (Product::lockForUpdate()->max('id') ?? 0) + 1;
                $code = $prefix . $nextId;
                if (!Product::where('code', $code)->exists()) {
                    break;
                }
                usleep(100000);
            }
        }

        if (empty($code)) {
            throw new \RuntimeException('Failed to generate unique code after multiple attempts.');
        }

        // Step 6: Prepare product data
        $productData = [
            'main_head_id'        => $data['main_head_id'] ?? null,
            'control_head_id'     => $data['control_head_id'] ?? null,
            'sub_head_id'         => $data['sub_head_id'] ?? null,
            'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
            'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
            'company_id'          => null,
            'project_id'          => $project ?? null,
            'road_id'             => null,
            'front_id'            => null,
            'amount_in_pkr'       => null,
            'total_amount'        => null,
            'unit_no'             => null,
            'block'               => null,
            'code'                => $code,
            'kanal'               => null,
            'marla'               => null,
            'front_width'         => null,
            'length'              => null,
            'front_width2'        => null,
            'length2'             => null,
            'total_marla'         => null,
            'square_feet'         => null,
            'total_square_feet'   => null,
            'status'              => $product->status ?? 'Item',
            'image'               => null,
            'name_en'             => $data['name_en'] ?? null,
            'name_ur'             => $data['name_ur'] ?? null,
            'termsAndConditions'  => null,
            'type'                => 'item',
        ];

        // Step 7: Update or create the product
        return Product::updateOrCreate(
            [
                'project_id' => $project,
                'name_en'    => $item->name_en,
                'name_ur'    => $item->name_ur,
            ],
            $productData
        );
    }

    public function updateDetailAccountData(array $data, int $id)
    {
        $item = Item::select('name_en', 'name_ur', 'sub_sub_sub_head_id')->where('id', $id)->first();
        $project = SubSubSubHead::find($item->sub_sub_sub_head_id)?->project_id;

        return DetailAccount::updateOrCreate(
            [
                'project_id' => $project,
                'name_en'    => $item->name_en,
                'name_ur'    => $item->name_ur,
            ],
            [
                'main_head_id'        => $data['main_head_id'] ?? null,
                'control_head_id'     => $data['control_head_id'] ?? null,
                'sub_head_id'         => $data['sub_head_id'] ?? null,
                'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
                'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
                'party_id'            => null,
                'project_id'          => $project ?? null,
                'name_en'             => $data['name_en'] ?? null,
                'name_ur'             => $data['name_ur'] ?? null,
            ]
        );
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $project = SubSubSubHead::find($item->sub_sub_sub_head_id)?->project_id;
        DetailAccount::where('project_id', $project)
            ->where('name_en', $item->name_en)
            ->where('name_ur', $item->name_ur)
            ->delete();
        Product::where('project_id', $project)
            ->where('name_en', $item->name_en)
            ->where('name_ur', $item->name_ur)
            ->delete();
        return $item->delete();
    }
}
