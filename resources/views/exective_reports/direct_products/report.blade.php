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

    @foreach ($groupedProjects as $projectId => $projectProducts)
        @php
            $projectName = $projectProducts->first()->project ? ($isUrdu ? $projectProducts->first()->project->name_ur : $projectProducts->first()->project->name_en) : 'N/A';
            $projectMarlaAll = $projectProducts->sum('total_marla');
            $projectAmountAll = $projectProducts->sum('total_amount');
            $projectMarlaBooked = $projectProducts->filter(fn($product) => strtolower((string) $product->status) === 'booked')->sum('total_marla');
            $projectAmountBooked = \App\Models\BookingApplication::whereIn('product_id', $projectProducts->pluck('id'))
                ->sum('total_amount');
            $projectMarlaVerified = $projectProducts->filter(fn($product) => strtolower((string) $product->status) === 'verified')->sum('total_marla');
            $projectAmountVerified = $projectProducts->filter(fn($product) => strtolower((string) $product->status) === 'verified')->sum('total_amount');
        @endphp

        <div class="project-header" style="display:flex;justify-content:space-between;align-items:center;">
            <strong>{{ $projectName }}</strong>
        </div>

        <table>
            <thead>
                <tr>
                    <th>@lang('messages.project')</th>
                    <th>@lang('messages.total_marla')</th>
                    <th>@lang('messages.total_amount')</th>
                    <th>@lang('messages.booked') / @lang('messages.total_marla')</th>
                    <th>@lang('messages.booked') / @lang('messages.total_amount')</th>
                    <th>@lang('messages.verified') / @lang('messages.total_marla')</th>
                    <th>@lang('messages.verified') / @lang('messages.total_amount')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $projectName }}</td>
                    <td>{{ number_format($projectMarlaAll, 2) }}</td>
                    <td>{{ number_format($projectAmountAll, 2) }}</td>
                    <td>{{ number_format($projectMarlaBooked, 2) }}</td>
                    <td>{{ number_format($projectAmountBooked, 2) }}</td>
                    <td>{{ number_format($projectMarlaVerified, 2) }}</td>
                    <td>{{ number_format($projectAmountVerified, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <h3>{{ $isUrdu ? 'کل خلاصہ' : 'Grand Summary' }}</h3>

    <table>
        <thead>
            <tr>
                <th>{{ $isUrdu ? 'کل مرلہ' : 'Total Marla' }}</th>
                <th>{{ $isUrdu ? 'کل رقم' : 'Total Amount' }}</th>
                <th>{{ $isUrdu ? 'بک شدہ کل مرلہ' : 'Booked Total Marla' }}</th>
                <th>{{ $isUrdu ? 'بک شدہ کل رقم' : 'Booked Total Amount' }}</th>
                <th>{{ $isUrdu ? 'تصدیق شدہ کل مرلہ' : 'Verified Total Marla' }}</th>
                <th>{{ $isUrdu ? 'تصدیق شدہ کل رقم' : 'Verified Total Amount' }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="total-row">
                <td>{{ number_format($grandTotals['marla_all'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_all'], 2) }}</td>
                <td>{{ number_format($grandTotals['marla_booked'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_booked'], 2) }}</td>
                <td>{{ number_format($grandTotals['marla_verified'], 2) }}</td>
                <td>{{ number_format($grandTotals['amount_verified'], 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
