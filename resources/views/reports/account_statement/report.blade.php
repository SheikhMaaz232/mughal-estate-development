@extends('layouts.backend')

@section('content')

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- REPORT HEADER --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <h3 class="mb-1">

                            {{ __('Account Statement') }}

                        </h3>


                        <h5 class="mb-1">

                            @if (app()->getLocale() === 'ur')
                                {{ $account->name_ur }}
                            @else
                                {{ $account->name_en }}
                            @endif

                        </h5>


                        @if ($account->party)
                            <div>

                                <strong>
                                    @lang('messages.party'):
                                </strong>

                                @if (app()->getLocale() === 'ur')
                                    {{ $account->party->name_ur ?? ($account->party->name_en ?? '-') }}
                                @else
                                    {{ $account->party->name_en ?? ($account->party->name_ur ?? '-') }}
                                @endif

                            </div>
                        @endif

                    </div>


                    <div class="col-md-4 text-md-right">

                        <strong>
                            {{ __('Period') }}
                        </strong>

                        <br>

                        {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}

                        &nbsp; {{ __('To') }} &nbsp;

                        {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- ACCOUNT HIERARCHY --}}
        {{-- ========================================================= --}}

        <div class="card mt-3">

            <div class="card-body">

                <div class="row">

                    @if ($account->mainHead)
                        <div class="col-md-2">

                            <strong>
                                {{ __('Main Head') }}
                            </strong>

                            <br>

                            {{ $account->mainHead->name_en ?? '-' }}

                        </div>
                    @endif


                    @if ($account->controlHead)
                        <div class="col-md-2">

                            <strong>
                                {{ __('Control Head') }}
                            </strong>

                            <br>

                            {{ $account->controlHead->name_en ?? '-' }}

                        </div>
                    @endif


                    @if ($account->subHead)
                        <div class="col-md-2">

                            <strong>
                                {{ __('Sub Head') }}
                            </strong>

                            <br>

                            {{ $account->subHead->name_en ?? '-' }}

                        </div>
                    @endif


                    @if ($account->subSubHead)
                        <div class="col-md-2">

                            <strong>
                                {{ __('Sub Sub Head') }}
                            </strong>

                            <br>

                            {{ $account->subSubHead->name_en ?? '-' }}

                        </div>
                    @endif


                    @if ($account->subSubSubHead)
                        <div class="col-md-2">

                            <strong>
                                {{ __('Sub Sub Sub Head') }}
                            </strong>

                            <br>

                            {{ $account->subSubSubHead->name_en ?? '-' }}

                        </div>
                    @endif


                    <div class="col-md-2">

                        <strong>
                            {{ __('Detail Account') }}
                        </strong>

                        <br>

                        {{ $account->name_en }}

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="row mt-3">


            {{-- Opening --}}
            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <small class="text-muted">

                            {{ __('Opening Balance') }}

                        </small>

                        <h5 class="mb-0">

                            {{ number_format(abs($openingBalance), 2) }}

                            @if ($openingBalance > 0)
                                <span class="text-success">
                                    Dr
                                </span>
                            @elseif($openingBalance < 0)
                                <span class="text-danger">
                                    Cr
                                </span>
                            @else
                                <span>
                                    -
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
            {{-- Debit --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            {{ __('Total Debit') }}
                        </small>
                        <h5 class="mb-0">
                            {{ number_format($totalDebit, 2) }}
                        </h5>
                    </div>
                </div>
            </div>

            {{-- Credit --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            {{ __('Total Credit') }}
                        </small>
                        <h5 class="mb-0">
                            {{ number_format($totalCredit, 2) }}
                        </h5>
                    </div>
                </div>
            </div>
            {{-- Closing --}}

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            {{ __('Closing Balance') }}
                        </small>
                        <h5 class="mb-0">
                            {{ number_format(abs($closingBalance), 2) }}

                            @if ($closingBalance > 0)
                                <span class="text-success">
                                    Dr
                                </span>
                            @elseif($closingBalance < 0)
                                <span class="text-danger">
                                    Cr
                                </span>
                            @else
                                <span>
                                    -
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- ========================================================= --}}
        {{-- ACTION BUTTONS --}}
        {{-- ========================================================= --}}

        <div class="card mt-3 no-print">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <button type="button" onclick="window.print()" class="btn btn-secondary mr-2">

                        <i class="fa fa-print"></i>

                        {{ __('Print') }}

                    </button>

                    <a href="{{ route('reports.account-statement.index') }}" class="btn btn-primary">

                        <i class="fa fa-filter"></i>

                        {{ __('Change Filter') }}
                    </a>
                </div>
            </div>
        </div>
        {{-- ========================================================= --}}
        {{-- STATEMENT TABLE --}}
        {{-- ========================================================= --}}

        <div class="card mt-3">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                            
                                <th>
                                    {{ __('Date') }}
                                </th>

                                <th>
                                    {{ __('Document No.') }}
                                </th>

                                <th>
                                    {{ __('Transaction Type') }}
                                </th>

                                <th>
                                    {{ __('Description') }}
                                </th>

                                <th class="text-right">
                                    {{ __('Debit') }}
                                </th>

                                <th class="text-right">
                                    {{ __('Credit') }}
                                </th>

                                <th class="text-right">
                                    {{ __('Balance') }}
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            {{-- ================================================= --}}
                            {{-- OPENING BALANCE --}}
                            {{-- ================================================= --}}

                            <tr class="font-weight-bold">

                                <td colspan="4">

                                    {{ __('Opening Balance') }}

                                </td>

                                <td class="text-right">

                                    @if ($openingDebit > 0)
                                        {{ number_format($openingDebit, 2) }}
                                    @else
                                        -
                                    @endif

                                </td>

                                <td class="text-right">

                                    @if ($openingCredit > 0)
                                        {{ number_format($openingCredit, 2) }}
                                    @else
                                        -
                                    @endif

                                </td>


                                <td class="text-right">

                                    {{ number_format(abs($openingBalance), 2) }}

                                    @if ($openingBalance > 0)
                                        Dr
                                    @elseif($openingBalance < 0)
                                        Cr
                                    @endif

                                </td>

                            </tr>
                            {{-- ================================================= --}}
                            {{-- TRANSACTIONS --}}
                            {{-- ================================================= --}}

                            @forelse($transactions as $transaction)
                                <tr>
                                    {{-- Date --}}
                                    <td>

                                        {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}

                                    </td>

                                    {{-- Document Number --}}
                                    <td>

                                        {{ $transaction->document_number ?? '-' }}

                                    </td>

                                    {{-- Transaction Type --}}
                                    <td>

                                        {{ ucwords(str_replace('_', ' ', $transaction->transaction_type ?? '-')) }}

                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        @if (app()->getLocale() === 'ur')
                                            {{ $transaction->description_ur ?? ($transaction->description_en ?? '-') }}
                                        @else
                                            {{ $transaction->description_en ?? ($transaction->description_ur ?? '-') }}
                                        @endif

                                        @if ($transaction->party)
                                            <br>

                                            <small class="text-muted">

                                                {{ __('Party') }}:
                                                {{ $transaction->party->name ?? '-' }}

                                            </small>
                                        @endif

                                        @if ($transaction->project)
                                            <br>

                                            <small class="text-muted">

                                                {{ __('Project') }}:
                                                {{ $transaction->project->name_ur ?? '-' }}
                                                 @if (app()->getLocale() === 'ur')
                                                    {{ $transaction->project->name_ur ?? ($transaction->project->name_en ?? '-') }}
                                                @else
                                                    {{ $transaction->project->name_en ?? ($transaction->project->name_ur ?? '-') }}
                                                @endif

                                            </small>
                                        @endif
                                    </td>

                                    {{-- Debit --}}
                                    <td class="text-right">

                                        @if ((float) $transaction->debit > 0)
                                            {{ number_format($transaction->debit, 2) }}
                                        @else
                                            -
                                        @endif

                                    </td>



                                    {{-- Credit --}}
                                    <td class="text-right">

                                        @if ((float) $transaction->credit > 0)
                                            {{ number_format($transaction->credit, 2) }}
                                        @else
                                            -
                                        @endif

                                    </td>



                                    {{-- Running Balance --}}
                                    <td class="text-right font-weight-bold">

                                        {{ number_format(abs($transaction->running_balance), 2) }}


                                        @if ($transaction->running_balance > 0)
                                            <span class="text-success">
                                                Dr
                                            </span>
                                        @elseif($transaction->running_balance < 0)
                                            <span class="text-danger">
                                                Cr
                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-4">

                                        {{ __('No transactions found for the selected period.') }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>



                        {{-- ================================================= --}}
                        {{-- FOOTER --}}
                        {{-- ================================================= --}}

                        <tfoot>

                            <tr class="font-weight-bold">

                                <td colspan="4" class="text-right">

                                    {{ __('Total') }}

                                </td>


                                <td class="text-right">

                                    {{ number_format($totalDebit, 2) }}

                                </td>


                                <td class="text-right">

                                    {{ number_format($totalCredit, 2) }}

                                </td>


                                <td class="text-right">

                                    {{ number_format(abs($closingBalance), 2) }}


                                    @if ($closingBalance > 0)
                                        Dr
                                    @elseif($closingBalance < 0)
                                        Cr
                                    @endif

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================= --}}
    {{-- PRINT CSS --}}
    {{-- ============================================================= --}}

    <style>
        @media print {

            @page {

                size: landscape;
                margin: 10mm;

            }


            body {

                background: #ffffff !important;

            }


            .no-print,
            .sidebar,
            .navbar,
            .main-header,
            .main-sidebar,
            .footer {

                display: none !important;

            }


            .container-fluid {

                width: 100% !important;
                max-width: 100% !important;

                padding: 0 !important;
                margin: 0 !important;

            }


            .card {

                border: none !important;
                box-shadow: none !important;

            }


            .table {

                width: 100% !important;

            }


            .table th,
            .table td {

                padding: 5px !important;
                font-size: 11px !important;

            }

        }
    </style>

@endsection
