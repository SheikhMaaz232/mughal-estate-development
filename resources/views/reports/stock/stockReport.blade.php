<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ App::getLocale() == 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">

    <title>
        @lang('messages.stock_report')
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        thead {
            background: #34495e;
            color: #fff;
        }

        .total-row {
            font-weight: bold;
            background: #f5f5f5;
        }

        .filter-box {
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <div style="margin-bottom:15px">

        <button onclick="window.print()" class="btn btn-success">

            Print Report

        </button>

    </div>

    @php
        $isUrdu = app()->getLocale() == 'ur';
    @endphp

    <div class="report-header">

        <h2>@lang('messages.company_name')</h2>

        <h3>@lang('messages.stock_report')</h3>

    </div>
    <div style="margin-bottom:15px">

        <strong>
            @lang('messages.total_stock_in'):
        </strong>

        {{ number_format($totalIn, 2) }}

        <br>

        <strong>
            @lang('messages.total_stock_out'):
        </strong>

        {{ number_format($totalOut, 2) }}

        <br>

        <strong>
            @lang('messages.closing_stock'):
        </strong>

        {{ number_format($closingStock, 2) }}

    </div>

    <table>

        <thead>

            <tr>

                <th>@lang('messages.date')</th>

                <th>@lang('messages.project')</th>

                <th>@lang('messages.product')</th>

                <th>@lang('messages.party')</th>

                <th>@lang('messages.document_no')</th>

                <th>@lang('messages.description')</th>

                <th>@lang('messages.stock_in')</th>

                <th>@lang('messages.stock_out')</th>

                <th>@lang('messages.balance')</th>

            </tr>

        </thead>

        <tbody>

            @php
                $balance = 0;
            @endphp

            @forelse($stockLedger as $row)
                <tr>

                    <td>
                        {{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $isUrdu ? $row->project->name_ur : $row->project->name_en }}
                    </td>

                    <td>
                        {{ $isUrdu ? $row->product->name_ur : $row->product->name_en }}
                    </td>

                    <td>
                        {{ $isUrdu ? $row->party_title_ur : $row->party_title_en }}
                    </td>

                    <td>
                        {{ $row->document_number }}
                    </td>

                    <td>
                        {{ $isUrdu ? $row->description_ur : $row->description_en }}
                    </td>

                    <td>
                        {{ number_format($row->stock_in_quantity, 2) }}
                    </td>

                    <td>
                        {{ number_format($row->stock_out_quantity, 2) }}
                    </td>

                    <td>
                        {{ number_format($row->balance, 2) }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="9">
                        @lang('messages.no_records_found')
                    </td>

                </tr>
            @endforelse

            <tr class="total-row">

                <td colspan="6">

                    @lang('messages.total')

                </td>

                <td>

                    {{ number_format($totalIn, 2) }}

                </td>

                <td>

                    {{ number_format($totalOut, 2) }}

                </td>

                <td>

                    {{ number_format($closingStock, 2) }}

                </td>

            </tr>

        </tbody>

    </table>

</body>

</html>
