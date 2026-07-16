<?php

namespace App\Http\Controllers\LandRegistration;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Land;
use App\Models\LandTransfer;
use App\Models\Project;
use App\Models\RegistryType;
use App\Models\DetailAccount;
use App\Models\Moza;
use App\Models\Tehsil;
use App\Models\City;

class LandReportController extends Controller
{
    public function areaSummaryReport(Request $request)
    {
        // Get filter parameters
        $filters = $request->all();

        // Build query using Laravel Query Builder
        $query = DB::table('lands')
            ->select(
                'lands.id',
                'lands.seller_account_id',
                'lands.buyer_account_id',
                'lands.project_id',
                'lands.total_marla',
                'lands.total_acre',
                'lands.total_kanal',
                'lands.total_square_feet',
                'land_details.registry_no',
                'lands.land_amount',
                'lands.commission_amount',
                'lands.created_at',
                'land_transfers.khawat_no',
                'land_transfers.transfer_date',
                'land_transfers.fard_no',
                'land_transfers.registry_type_id',
                'detail_accounts.name_ur as party_name',
                'registry_types.title_ur as registry_type_name',
                DB::raw('SUM(lands.total_marla) as total_marla_sum'),
                DB::raw('GROUP_CONCAT(lands.id) as registry_detail_ids')
            )
            ->leftJoin('land_transfers', 'lands.id', '=', 'land_transfers.land_id')
            ->leftJoin('detail_accounts', 'detail_accounts.id', '=', 'lands.buyer_account_id')
            ->leftJoin('registry_types', 'land_transfers.registry_type_id', '=', 'registry_types.id')
            ->leftJoin('land_details', 'land_details.land_id', '=', 'lands.id')
            ->groupBy(
                'land_transfers.khawat_no',
                'lands.id',
                'lands.seller_account_id',
                'lands.buyer_account_id',
                'lands.project_id',
                'lands.total_marla',
                'lands.total_acre',
                'lands.total_kanal',
                'lands.total_square_feet',
                'land_details.registry_no',
                'lands.land_amount',
                'lands.commission_amount',
                'lands.created_at',
                'land_transfers.khawat_no',
                'land_transfers.transfer_date',
                'land_transfers.fard_no',
                'land_transfers.registry_type_id',
                'detail_accounts.name_ur',
                'registry_types.title_ur',
            );

        // Apply filters
        if (!empty($filters['cnic_no'])) {
            $query->where('lands.seller_account_id', $filters['cnic_no']);
        }

        if (!empty($filters['to_cnic_no'])) {
            $query->where('lands.buyer_account_id', $filters['to_cnic_no']);
        }

        if (!empty($filters['party_name'])) {
            $query->where('lands.seller_account_id', $filters['party_name']);
        }

        if (!empty($filters['to_party_name'])) {
            $query->where('lands.buyer_account_id', $filters['to_party_name']);
        }

        if (!empty($filters['khawat_no'])) {
            $khawatNumbers = explode(',', $filters['khawat_no']);
            $query->whereIn('land_transfers.khawat_no', $khawatNumbers);
        }

        if (!empty($filters['registry_type_name'])) {
            $query->where('land_transfers.registry_type_id', $filters['registry_type_name']);
        }

        if (!empty($filters['project_name'])) {
            $query->where('lands.project_id', $filters['project_name']);
        }

        // Get the data
        $landData = $query->orderBy('lands.id')->get();

        // Calculate totals and prepare data
        $reportData = $this->prepareReportData($landData);

        // Get dropdown data for filters
        $dropdownData = $this->getDropdownData();

        return view('land-registration.reports.area-summary-report', compact('reportData', 'filters', 'dropdownData'));
    }

    // Alternative method using Laravel Eloquent (Recommended)
    public function areaSummaryReportEloquent(Request $request)
    {
        $filters = $request->all();

        // Start with Land model
        $query = Land::with(['transfers', 'sellerAccount', 'buyerAccount', 'project'])
            ->select([
                'id',
                'seller_account_id',
                'buyer_account_id',
                'project_id',
                'total_marla',
                'total_acre',
                'total_kanal',
                'total_square_feet',
                'land_amount',
                'commission_amount',
                'created_at'
            ])
            ->withCount('transfers')
            ->withSum('transfers as transfers_total_marla', 'total_marla');

        // Apply filters
        if (!empty($filters['cnic_no'])) {
            $query->where('seller_account_id', $filters['cnic_no']);
        }

        if (!empty($filters['to_cnic_no'])) {
            $query->where('buyer_account_id', $filters['to_cnic_no']);
        }

        if (!empty($filters['khawat_no'])) {
            $khawatNumbers = explode(',', $filters['khawat_no']);
            $query->whereHas('transfers', function($q) use ($khawatNumbers) {
                $q->whereIn('khawat_no', $khawatNumbers);
            });
        }

        if (!empty($filters['project_name'])) {
            $query->where('project_id', $filters['project_name']);
        }

        if (!empty($filters['registry_type_name'])) {
            $query->whereHas('transfers', function($q) use ($filters) {
                $q->where('registry_type_id', $filters['registry_type_name']);
            });
        }

        $lands = $query->orderBy('id')->get();

        // Group by khawat_no for reporting
        $groupedData = $this->groupByKhawatNo($lands);

        $reportData = $this->prepareReportDataEloquent($groupedData, $lands);
        $dropdownData = $this->getDropdownData();

        return view('land-registration.reports.area-summary-report', compact('reportData', 'filters', 'dropdownData'));
    }

    private function groupByKhawatNo($lands)
    {
        $grouped = [];

        foreach ($lands as $land) {
            foreach ($land->transfers as $transfer) {
                $khawatNo = $transfer->khawat_no ?? 'N/A';

                if (!isset($grouped[$khawatNo])) {
                    $grouped[$khawatNo] = [
                        'lands' => [],
                        'total_marla' => 0,
                        'total_land_amount' => 0,
                        'transfers' => []
                    ];
                }

                $grouped[$khawatNo]['lands'][] = $land;
                $grouped[$khawatNo]['total_marla'] += $land->total_marla ?? 0;
                $grouped[$khawatNo]['total_land_amount'] += $land->land_amount ?? 0;
                $grouped[$khawatNo]['transfers'][] = $transfer;
            }
        }

        return $grouped;
    }

    private function prepareReportDataEloquent($groupedData, $lands)
    {
        $totalMarlas = 0;
        $registryDetailIds = [];
        $regDetailIds = [];

        foreach ($lands as $land) {
            $totalMarlas += $land->total_marla ?? 0;
            $registryDetailIds[] = $land->id;
            $regDetailIds[] = $land->id;
        }

        $registryDetailIdsString = implode('-', $registryDetailIds);
        $regDetailIdsString = implode(',', $regDetailIds);
        $registryDetailIdsArray = $registryDetailIds;

        // Calculate transfer areas (simplified - you'll need to implement based on your business logic)
        $transferData = $this->calculateTransferAreasEloquent($regDetailIdsString);

        // Calculate balance areas
        $balanceData = $this->calculateBalanceAreasEloquent($registryDetailIdsArray, $totalMarlas);

        return [
            'groupedData' => $groupedData,
            'lands' => $lands,
            'totalMarlas' => $totalMarlas,
            'totalInMarla' => $this->convertMarlaToKanalMarla($totalMarlas),
            'registryDetailIdsArray' => $registryDetailIdsArray,
            'transferData' => $transferData,
            'balanceData' => $balanceData,
            'regDetailIds' => $regDetailIdsString
        ];
    }

    private function calculateTransferAreasEloquent($regDetailIds)
    {
        // Query for transfer lands based on your business logic
        // This is a simplified version - adjust according to your relationships
        $transferLands = Land::whereIn('id', explode(',', $regDetailIds))
            ->whereHas('transfers')
            ->get();

        $totalOutMarla = $transferLands->sum('total_marla') * 0.3; // Example: 30% transferred

        return [
            'totalOutMarla' => $totalOutMarla,
            'totalOutConverted' => $this->convertMarlaToKanalMarla($totalOutMarla),
            'transferLands' => $transferLands
        ];
    }

    private function calculateBalanceAreasEloquent($registryDetailIdsArray, $totalMarlas)
    {
        // Calculate balance based on your business logic
        $totalBalanceInMarla = $totalMarlas;
        $totalBalanceOutMarla = $totalMarlas * 0.3; // Example: 30% transferred
        $grandTotalBalance = $totalBalanceInMarla - $totalBalanceOutMarla;

        return [
            'grandTotalBalance' => $grandTotalBalance,
            'grandTotalConverted' => $this->convertMarlaToKanalMarla($grandTotalBalance),
            'totalBalanceInMarla' => $totalBalanceInMarla,
            'totalBalanceOutMarla' => $totalBalanceOutMarla
        ];
    }

    private function prepareReportData($landData)
    {
        $totalMarlas = 0;
        $registryDetailIds = [];
        $regDetailIds = [];

        foreach ($landData as $land) {
            $totalMarlas += $land->total_marla_sum ?? $land->total_marla ?? 0;
            if ($land->registry_detail_ids) {
                $ids = explode(',', $land->registry_detail_ids);
                $registryDetailIds = array_merge($registryDetailIds, $ids);
                $regDetailIds = array_merge($regDetailIds, $ids);
            } else {
                $registryDetailIds[] = $land->id;
                $regDetailIds[] = $land->id;
            }
        }

        $registryDetailIdsString = implode('-', array_unique($registryDetailIds));
        $regDetailIdsString = implode(',', array_unique($regDetailIds));
        $registryDetailIdsArray = array_unique($registryDetailIds);

        $transferData = $this->calculateTransferAreas($regDetailIdsString);
        $balanceData = $this->calculateBalanceAreas($registryDetailIdsArray, $totalMarlas);

        return [
            'landData' => $landData,
            'totalMarlas' => $totalMarlas,
            'totalInMarla' => $this->convertMarlaToKanalMarla($totalMarlas),
            'registryDetailIdsArray' => $registryDetailIdsArray,
            'transferData' => $transferData,
            'balanceData' => $balanceData,
            'regDetailIds' => $regDetailIdsString
        ];
    }

    private function calculateTransferAreas($regDetailIds)
    {
        // Simplified calculation - replace with your actual business logic
        return [
            'totalOutMarla' => 500,
            'totalOutConverted' => $this->convertMarlaToKanalMarla(500)
        ];
    }

    private function calculateBalanceAreas($registryDetailIdsArray, $totalMarlas)
    {
        $totalBalanceInMarla = $totalMarlas;
        $totalBalanceOutMarla = 500; // Example value
        $grandTotalBalance = $totalBalanceInMarla - $totalBalanceOutMarla;

        return [
            'grandTotalBalance' => $grandTotalBalance,
            'grandTotalConverted' => $this->convertMarlaToKanalMarla($grandTotalBalance)
        ];
    }

    private function convertMarlaToKanalMarla($marla)
    {
        $kanal = floor($marla / 20);
        $remainingMarla = $marla % 20;
        $yard = 0;

        return [
            'kanal' => $kanal,
            'marla' => $remainingMarla,
            'yard' => $yard
        ];
    }

    private function getDropdownData()
    {
        return [
            'projects' => Project::all(),
            'registryTypes' => RegistryType::all(),
            'sellers' => DetailAccount::where('sub_sub_sub_head_id', 5)
            ->orderBy('id')
            ->select('name_en','name_ur', 'id')
            ->get(),
            'buyers' => DetailAccount::where('sub_sub_sub_head_id', 5)
            ->orderBy('id')
            ->select('name_en','name_ur', 'id')
            ->get(),
            'mozas' => Area::all(),
            'tehsils' => Tehsil::all(),
            'cities' => City::all(),
        ];
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->all();
        // Implement PDF export
        return response()->json(['message' => 'PDF export functionality']);
    }
}
