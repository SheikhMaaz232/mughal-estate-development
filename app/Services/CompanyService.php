<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Services\CommonService;

class CompanyService
{
    protected $commonService;

    public function __construct(CommonService $commonService) {
        $this->commonService = $commonService;
    }

    public function paginateCompanies(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return Company::query()
            ->when($filters['search'] ?? null, fn($query, $search) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate($perPage)
            ->through(fn($company) => [
                'id' => $company->id,
                'name_en' => $company->name,
                'name_ur' => $company->name
            ]);
    }

    public function create(array $data, $image = null): Company
    {
        if ($image) {
            $data['logo'] = $this->commonService->uploadImage($image, 'company_images');
        }

        return Company::create($data);
    }

    public function update(Company $company, array $validatedData, array $data, $image = null): Company
    {

        if ($image) {
            // Optional: delete old image
            if ($company->image && Storage::exists($company->image)) {
                Storage::delete($company->image);
            }

            $data['logo'] = $this->commonService->uploadImage($image, 'company_images');
        }

        $company->update($data);
        return $company;
    }

    public function deleteCompany(Company $company): void
    {
        Storage::disk('public')->delete($company->logo);
        $company->delete();
    }

    public function getCompanyForEditing(Company $company): array
    {
        return $company->only('id', 'name', 'email', 'logo', 'website', 'description');
    }
}
