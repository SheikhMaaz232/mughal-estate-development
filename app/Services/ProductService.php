<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Project;
use App\Models\DetailAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getAll($perPage = 10)
    {
        return Product::with('mainHead', 'controlHead', 'subHead', 'subSubHead', 'company', 'baseVolumeUnit', 'baseCoverageUnit')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data, $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            $prefix = 'Pro-' . implode('', [
                $data['main_head_id'],
                $data['control_head_id'],
                $data['sub_head_id'],
                $data['sub_sub_head_id'],
                $data['sub_sub_sub_head_id'],
            ]);

            for ($i = 0; $i < 5; $i++) {
                // Lock matching rows and get max number
                $nextId = (Product::lockForUpdate()->max('id') ?? 0) + 1;

                $data['code'] = $prefix . $nextId;

                try {
                    // Create product (include image handling if needed)
                    if ($image) {
                        $data['image'] = $this->commonService->uploadImage($image, 'unitRegistrations');
                    }
                    return Product::create($data);
                } catch (QueryException $e) {
                    if (!in_array($e->getCode(), ['23000', '23505'])) throw $e;
                    usleep(100_000); // wait 0.1s then retry
                }
            }

            throw new \RuntimeException('Failed to generate unique code after multiple attempts.');
        });
    }

    public function update(int $id, array $data, $image = null): Product
    {
        return DB::transaction(function () use ($id, $data, $image) {
            $product = Product::lockForUpdate()->findOrFail($id);

            if ($image) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $this->commonService->uploadImage($image, 'unitRegistrations');
            }

            $prefix = implode('', [
                $data['main_head_id'],
                $data['control_head_id'],
                $data['sub_head_id'],
                $data['sub_sub_head_id'],
                $data['sub_sub_sub_head_id'],
            ]);

            $data['code'] = 'Pro-' . $prefix . $id;

            $product->update($data);

            return $product;
        });
    }


    public function createFromProduct(array $data)
    {

        $detailAccountData = [
            'main_head_id'        => $data['main_head_id'] ?? null,
            'control_head_id'     => $data['control_head_id'] ?? null,
            'sub_head_id'         => $data['sub_head_id'] ?? null,
            'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
            'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
            'party_id'            => null,
            'project_id'          => $data['project_id'] ?? null,
            'name_en'             => $data['name_en'] ?? null,
            'name_ur'             => $data['name_ur'] ?? null,
        ];
        // Save
        return DetailAccount::create($detailAccountData);
    }

    public function updateFromProduct(array $data, int $id)
    {
        $product = Product::select('project_id', 'name_en', 'name_ur')->where('id', $id)->first();

        return DetailAccount::updateOrCreate(
            [
                'project_id' => $product->project_id,
                'name_en'    => $product->name_en,
                'name_ur'    => $product->name_ur,
            ],
            [
                'main_head_id'        => $data['main_head_id'] ?? null,
                'control_head_id'     => $data['control_head_id'] ?? null,
                'sub_head_id'         => $data['sub_head_id'] ?? null,
                'sub_sub_head_id'     => $data['sub_sub_head_id'] ?? null,
                'sub_sub_sub_head_id' => $data['sub_sub_sub_head_id'] ?? null,
                'party_id'            => null,
                'project_id'          => $data['project_id'] ?? null,
                'name_en'             => $data['name_en'] ?? null,
                'name_ur'             => $data['name_ur'] ?? null,
            ]
        );
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        DetailAccount::where('project_id', $product->project_id)
            ->where('name_en', $product->name_en)
            ->delete();

        // Delete product
        return $product->delete();
    }

    public function generateDetailAccountCode($code)
    {
        return DB::transaction(function () use ($code) {
            // Lock the table to prevent concurrent access
            $getDetailAccount = Product::lockForUpdate()->max('id');
            $nextId = $getDetailAccount ? $getDetailAccount + 1 : 1;

            return 'Pro-' . $code . $nextId;
        });
    }

    public function projectSquareFeet($projectId)
    {
        return Project::select('square_feet', 'company_id')
            ->where('id', $projectId)
            ->first();
    }
}
