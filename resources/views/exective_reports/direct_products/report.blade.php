<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>
        {{ app()->getLocale() == 'ur' ? 'ڈائریکٹ پروڈکٹس پروجیکٹ رپورٹ' : 'Direct Products Project Report' }}
    </title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        thead {
            background: #34495e;
            color: white;
        }

        .project-header {
            background: #dfe6e9;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
        }

        .total-row {
            background: #ecf0f1;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        $isUrdu = app()->getLocale() == 'ur';
    @endphp

    <h2 style="text-align:center;">
        {{ $isUrdu ? 'پروجیکٹ وار ڈائریکٹ پروڈکٹس رپورٹ' : 'Project Wise Direct Products Report' }}
    </h2>

    @php
        $grandPossessionFees = 0;
        $grandPossessionFeesReceived = 0;
        $grandProceedingFees = 0;
        $grandProceedingFeesReceived = 0;
        $grandDevelopmentCharges = 0;
        $grandDevelopmentChargesReceived = 0;
        $grandGST = 0;
        $grandGSTReceived = 0;
        $grandRegistryFees = 0;
        $grandRegistryFeesReceived = 0;
        $grandTransferCharges = 0;
        $grandTransferChargesReceived = 0;
    @endphp

    @foreach ($groupedProjects as $projectId => $projectProducts)
        @php
            $projectName = $projectProducts->first()->project
                ? ($isUrdu
                    ? $projectProducts->first()->project->name_ur
                    : $projectProducts->first()->project->name_en)
                : 'N/A';
            $projectMarlaAll = $projectProducts->sum('total_marla');
            $projectAmountAll = $projectProducts->sum('total_amount');
            $projectMarlaBooked = $projectProducts
                ->filter(fn($product) => strtolower((string) $product->status) === 'booked')
                ->sum('total_marla');

            $eligibleBookings = \App\Models\BookingApplication::where('case', '!=', 'ownership_changed')
                ->where('status', 'Verified')
                ->whereIn('product_id', $projectProducts->pluck('id'));

            $projectAmounts = (clone $eligibleBookings)
                ->selectRaw(
                    '
                        COALESCE(SUM(total_amount), 0) as total_amount,
                        COALESCE(SUM(possession_fees), 0) as possession_fees,
                        COALESCE(SUM(proceeding_fees), 0) as proceeding_fees,
                        COALESCE(SUM(development_charges), 0) as development_charges,
                        COALESCE(SUM(gst), 0) as gst
                ',
                )
                ->first();

            $projectBookingIds = (clone $eligibleBookings)->pluck('id');
            $registryFeeAccountIds = (clone $eligibleBookings)
                ->whereNotNull('detail_account_id')
                ->pluck('detail_account_id')
                ->unique();
            $projectRegistryFees = \App\Models\RegistryOrder::whereIn('booking_id', $projectBookingIds)
                ->sum('registry_fees');

            $transferBookings = \App\Models\BookingApplication::where('case', 'transfer')
                ->where('status', 'Verified')
                ->whereIn('product_id', $projectProducts->pluck('id'));
            $projectTransferCharges = (clone $transferBookings)->sum('transfer_charges');
            $transferFeeAccountIds = (clone $transferBookings)
                ->whereNotNull('detail_account_id')
                ->pluck('detail_account_id')
                ->unique();
            $projectTransferChargesReceived = \App\Models\AccountLedger::where('project_id', $projectId)
                ->whereIn('detail_account_id', $transferFeeAccountIds)
                ->where('transaction_type', 'transfer_charges')
                ->where(function ($query) {
                    $query->where('document_number', 'like', 'CRV-%')
                        ->orWhere('document_number', 'like', 'BRV-%');
                })
                ->sum('credit');

            // $projectRegistryFeesReceived = \App\Models\AccountLedger::where('project_id', $projectId)
            //     ->whereIn('detail_account_id', $registryFeeAccountIds)
            //     ->where('transaction_type', 'registry_fees')
            //     ->sum('credit');

            $projectAmountBooked = $projectAmounts->total_amount;
            $projectPlotsPossessionFees = $projectAmounts->possession_fees;
            $projectPlotsProceedingFees = $projectAmounts->proceeding_fees;
            $projectPlotsDevelopmentCharges = $projectAmounts->development_charges;
            $projectPlotsGST = $projectAmounts->gst;

            $projectMarlaVerified = $projectProducts
                ->filter(fn($product) => strtolower((string) $product->status) === 'verified')
                ->sum('total_marla');
            $projectAmountVerified = $projectProducts
                ->filter(fn($product) => strtolower((string) $product->status) === 'verified')
                ->sum('total_amount');
            $projectAmountReceived = $receivedAmountByProject[$projectId] ?? 0;
            $projectAmountReceivedPossession = $receivedPossessionAmountByProject[$projectId] ?? 0;
            $projectAmountReceivedProceeding = $receivedProceedingAmountByProject[$projectId] ?? 0;
            $projectAmountReceivedDevelopment = $receivedDevelopmentAmountByProject[$projectId] ?? 0;
            $projectAmountReceivedGST = $receivedGSTAmountByProject[$projectId] ?? 0;
            $projectAmountReceivedRegistry = $receivedRegistryAmountByProject[$projectId] ?? 0;

            $grandRegistryFees += $projectRegistryFees;
            $grandRegistryFeesReceived += $projectAmountReceivedRegistry;
            $grandTransferCharges += $projectTransferCharges;
            $grandTransferChargesReceived += $projectTransferChargesReceived;
            $grandPossessionFees += $projectPlotsPossessionFees;
            $grandPossessionFeesReceived += $projectAmountReceivedPossession;
            $grandProceedingFees += $projectPlotsProceedingFees;
            $grandProceedingFeesReceived += $projectAmountReceivedProceeding;
            $grandDevelopmentCharges += $projectPlotsDevelopmentCharges;
            $grandDevelopmentChargesReceived += $projectAmountReceivedDevelopment;
            $grandGST += $projectPlotsGST;
            $grandGSTReceived += $projectAmountReceivedGST;
        @endphp

        <div class="project-header" style="display:flex;justify-content:space-between;align-items:center;">
            <strong>{{ $projectName }}</strong>
        </div>

        <table>
            <thead>
                <tr>
                    <th>@lang('messages.project-name')</th>
                    <th>@lang('messages.total_marla')</th>
                    <th>@lang('messages.total_marlas_amount')</th>
                    <th>@lang('messages.total_saleable_marlas')</th>
                    <th>@lang('messages.total_saleable_marlas_amount')</th>
                    <th>@lang('messages.total_saleable_marlas_received_amount')</th>
                    <th>@lang('messages.total_available_marlas')</th>
                    <th>@lang('messages.total_available_marlas_value')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $projectName }}</td>
                    <td>{{ number_format($projectMarlaAll, 2) }}</td>
                    <td>{{ number_format($projectAmountAll, 2) }}</td>
                    <td>{{ number_format($projectMarlaBooked, 2) }}</td>
                    <td>{{ number_format($projectAmountBooked, 2) }}</td>
                    <td>{{ number_format($projectAmountReceived, 2) }}</td>
                    <td>{{ number_format($projectMarlaVerified, 2) }}</td>
                    <td>{{ number_format($projectAmountVerified, 2) }}</td>
                </tr>
            </tbody>

            <thead>
                <tr>
                    <th>{{ $isUrdu ? 'کل قبضہ فیس' : 'Tot Possession Fees' }}</th>
                    <th>{{ $isUrdu ? 'کل وصول شدہ قبضہ فیس' : 'Tot Possession Fee Collected' }}</th>
                    <th>{{ $isUrdu ? 'کل پروسیڈنگ فیس' : 'Tot Processing Fees' }}</th>
                    <th>{{ $isUrdu ? 'کل وصول شدہ پروسیڈنگ فیس' : 'Total Processing Fee Collected' }}</th>
                    <th>{{ $isUrdu ? 'کل ترقیاتی چارجز' : 'Total Development Charges' }}</th>
                    <th>{{ $isUrdu ? 'کل وصول شدہ ترقیاتی چارجز' : 'Tot Collected Development Charges' }}</th>
                    <th>{{ $isUrdu ? 'کل جنرل سیل ٹیکس' : 'Total General Sales Tax' }}</th>
                    <th>{{ $isUrdu ? 'کل وصول شدہ جنرل سیل ٹیکس' : 'Total General Sales Tax Collected' }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $projectPlotsPossessionFees }}</td>
                    <td>{{ number_format($projectAmountReceivedPossession, 2) }}</td>
                    <td>{{ number_format($projectPlotsProceedingFees, 2) }}</td>
                    <td>{{ number_format($projectAmountReceivedProceeding, 2) }}</td>
                    <td>{{ number_format($projectPlotsDevelopmentCharges, 2) }}</td>
                    <td>{{ number_format($projectAmountReceivedDevelopment, 2) }}</td>
                    <td>{{ number_format($projectPlotsGST, 2) }}</td>
                    <td>{{ number_format($projectAmountReceivedGST, 2) }}</td>
                </tr>
            </tbody>

            <thead>
                <tr>
                    <th>{{ $isUrdu ? 'کل رجسٹری فیس' : 'Tot Registry Fees' }}</th>
                    <th>{{ $isUrdu ? 'کل وصول شدہ رجسٹری فیس' : 'Total Registry Fee Collected' }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($projectRegistryFees, 2) }}</td>
                    <td>{{ number_format($projectAmountReceivedRegistry, 2) }}</td>
                </tr>
            </tbody>

        </table>
    @endforeach

    <h3>{{ $isUrdu ? 'کل خلاصہ' : 'Grand Summary' }}</h3>

    <table>
        <thead>
            <tr>
                <th>{{ $isUrdu ? 'کل مرلے' : 'Total Marla' }}</th>
                <th>{{ $isUrdu ? 'کل رقم' : 'Total Amount' }}</th>
                <th>{{ $isUrdu ? 'سیل شدہ کل مرلے' : 'Booked Total Marla' }}</th>
                <th>{{ $isUrdu ? 'سیل شدہ مرلوں کی کل رقم' : 'Booked Total Amount' }}</th>
                <th>{{ $isUrdu ? ' سیل شدہ مرلوں سے وصول شدہ کل رقم' : 'Total Received Amount' }}</th>
                <th>{{ $isUrdu ? 'کل دستیاب مرلے' : 'Verified Total Marla' }}</th>
                <th>{{ $isUrdu ? 'کل دستیاب مرلوں کی رقم' : 'Verified Total Amount' }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="total-row">
                <td>{{ number_format($grandTotals['marla_all'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_all'], 2) }}</td>
                <td>{{ number_format($grandTotals['marla_booked'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_booked'], 2) }}</td>
                <td>
                    {{ number_format($grandTotals['amount_received'], 2) }}
                </td>
                <td>{{ number_format($grandTotals['marla_verified'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_verified'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>{{ $isUrdu ? 'فیس' : 'Fee' }}</th>
                <th>{{ $isUrdu ? 'کل' : 'Total' }}</th>
                <th>{{ $isUrdu ? 'وصول شدہ' : 'Received' }}</th>
                <th>{{ $isUrdu ? 'فیس' : 'Fee' }}</th>
                <th>{{ $isUrdu ? 'کل' : 'Total' }}</th>
                <th>{{ $isUrdu ? 'وصول شدہ' : 'Received' }}</th>
                <th>{{ $isUrdu ? 'فیس' : 'Fee' }}</th>
                <th>{{ $isUrdu ? 'کل' : 'Total' }}</th>
                <th>{{ $isUrdu ? 'وصول شدہ' : 'Received' }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="total-row">
                <td>{{ $isUrdu ? 'قبضہ فیس' : 'Possession Fees' }}</td>
                <td>{{ number_format($grandPossessionFees, 2) }}</td>
                <td>{{ number_format($grandPossessionFeesReceived, 2) }}</td>
                <td>{{ $isUrdu ? 'پروسیڈنگ فیس' : 'Proceeding Fees' }}</td>
                <td>{{ number_format($grandProceedingFees, 2) }}</td>
                <td>{{ number_format($grandProceedingFeesReceived, 2) }}</td>
                <td>{{ $isUrdu ? 'ترقیاتی چارجز' : 'Development Charges' }}</td>
                <td>{{ number_format($grandDevelopmentCharges, 2) }}</td>
                <td>{{ number_format($grandDevelopmentChargesReceived, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>{{ $isUrdu ? 'جنرل سیل ٹیکس' : 'GST' }}</td>
                <td>{{ number_format($grandGST, 2) }}</td>
                <td>{{ number_format($grandGSTReceived, 2) }}</td>
                <td>{{ $isUrdu ? 'رجسٹری فیس' : 'Registry Fees' }}</td>
                <td>{{ number_format($grandRegistryFees, 2) }}</td>
                <td>{{ number_format($grandRegistryFeesReceived, 2) }}</td>
               
            </tr>
        </tbody>
    </table>
</body>

</html>
