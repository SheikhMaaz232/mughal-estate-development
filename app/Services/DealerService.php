<?php

namespace App\Services;

use App\Models\Dealer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Services\CommonService;

class DealerService
{
    protected $commonService;

    public function __construct(CommonService $commonService) {
        $this->commonService = $commonService;
    }

    public function paginateDealers(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return Dealer::query()
            ->when($filters['search'] ?? null, fn($query, $search) =>
                $query->where('name_en', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate($perPage)
            ->through(fn($dealer) => [
                'id' => $dealer->id,
                'name_en' => $dealer->name,
                'name_ur' => $dealer->name
            ]);
    }

    public function create(array $data, $image = null): Dealer
    {
        if ($image) {
            $data['photo'] = $this->commonService->uploadImage($image, 'dealers');
        }

        return Dealer::create($data);
    }

    public function update(Dealer $company, array $validatedData, array $data, $image = null): Dealer
    {

        if ($image) {
            // Optional: delete old image
            if ($company->image && Storage::exists($company->image)) {
                Storage::delete($company->image);
            }

            $data['logo'] = $this->commonService->uploadImage($image, 'dealers');
        }

        $company->update($data);
        return $company;
    }

    public function deleteCompany(Dealer $company): void
    {
        Storage::disk('public')->delete($company->logo);
        $company->delete();
    }

}
