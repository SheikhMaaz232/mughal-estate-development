<!DOCTYPE html>
<html lang="{{ $isUrdu ? 'ur' : 'en' }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('messages.financial_activity_report')</title>
    <style>
        :root { --ink: #17212b; --muted: #66727d; --line: #dce2e7; --blue: #1769aa; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 28px; color: var(--ink); font: 13px/1.5 "Segoe UI", Tahoma, sans-serif; background: #f4f6f8; }
        .sheet { max-width: 1500px; margin: auto; padding: 36px; background: #fff; box-shadow: 0 5px 25px rgba(23,33,43,.08); }
        .header { display: flex; justify-content: space-between; gap: 20px; border-bottom: 3px solid var(--ink); padding-bottom: 18px; margin-bottom: 22px; }
        h1, h2, h3, p { margin-top: 0; } h1 { margin-bottom: 4px; font-size: 26px; } h2 { margin-bottom: 4px; font-size: 19px; }
        .muted { color: var(--muted); } .period { text-align: {{ $isUrdu ? 'left' : 'right' }}; color: var(--muted); }
        .summary { margin: 20px 0 30px; } table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 9px; border: 1px solid var(--line); vertical-align: top; }
        th { color: var(--muted); background: #f4f7f9; text-align: {{ $isUrdu ? 'right' : 'left' }}; }
        .amount { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
        [dir="rtl"] .amount { text-align: left; }
        .project { margin: 28px 0; page-break-inside: auto; }
        .project-title { padding: 10px 14px; color: #fff; background: var(--ink); font-size: 17px; font-weight: 700; }
        .head { margin-top: 20px; page-break-inside: auto; }
        .head-title { padding: 8px 10px; color: #fff; background: var(--blue); font-size: 15px; }
        .account-name { font-weight: 700; }
        .account-total td { font-weight: 600; background: #fbfcfd; }
        .total td { font-weight: 700; background: #f4f7f9; }
        .empty { padding: 12px; color: var(--muted); }
        .print { display: inline-block; margin-bottom: 18px; padding: 8px 14px; color: #fff; background: var(--blue); border: 0; cursor: pointer; }
        .footer { margin-top: 30px; color: var(--muted); font-size: 11px; text-align: center; }
        @media print {
            body { padding: 0; background: #fff; }
            .sheet { max-width: none; padding: 16px; box-shadow: none; }
            .print { display: none; }
            tr, .project-title, .head-title { page-break-inside: avoid; }
        }
        @media (max-width: 800px) {
            body { padding: 8px; }
            .sheet { padding: 16px; overflow-x: auto; }
            .header { display: block; }
            .period { margin-top: 12px; text-align: {{ $isUrdu ? 'right' : 'left' }}; }
        }
    </style>
</head>
<body>
<main class="sheet">
    <button class="print" onclick="window.print()">@lang('messages.print_report')</button>
    <header class="header">
        <div>
            <h1>@lang('messages.company_name')</h1>
            <h2>@lang('messages.financial_activity_report')</h2>
            <p class="muted">@lang('messages.financial_activity_period_description')</p>
        </div>
        <div class="period">
            <strong>@lang('messages.reporting_period')</strong><br>
            {{ $fromDate->format('d M Y') }} - {{ $toDate->format('d M Y') }}
        </div>
    </header>

    <section class="summary">
        <h3>@lang('messages.consolidated_total')</h3>
        <table>
            <thead>
                <tr>
                    <th>@lang('messages.main_head')</th>
                    <th class="amount">@lang('messages.debit')</th>
                    <th class="amount">@lang('messages.credit')</th>
                    <th class="amount">@lang('messages.normal_balance')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grandHeadTotals as $head)
                    <tr>
                        <td>{{ $isUrdu ? ($head->name_ur ?: $head->name_en) : $head->name_en }}</td>
                        <td class="amount">Rs. {{ number_format($head->debit, 2) }}</td>
                        <td class="amount">Rs. {{ number_format($head->credit, 2) }}</td>
                        <td class="amount">Rs. {{ number_format($head->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    @forelse($projectWiseData as $project)
        <section class="project">
            <div class="project-title">{{ $isUrdu ? $project->project_name_ur : $project->project_name_en }}</div>
            @foreach($project->main_heads as $head)
                @if($head->accounts->isNotEmpty())
                    <section class="head">
                        <h3 class="head-title">{{ $isUrdu ? ($head->name_ur ?: $head->name_en) : $head->name_en }}</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>@lang('messages.transaction_date')</th>
                                    <th>@lang('messages.voucher_number')</th>
                                    <th>@lang('messages.account')</th>
                                    <th>@lang('messages.party')</th>
                                    <th>@lang('messages.narration')</th>
                                    <th class="amount">@lang('messages.debit')</th>
                                    <th class="amount">@lang('messages.credit')</th>
                                    <th class="amount">@lang('messages.normal_balance')</th>
                                </tr>
                            </thead>
                            @foreach($head->accounts as $account)
                                <tbody>
                                    @foreach($account->entries as $entry)
                                        <tr>
                                            <td>{{ $entry->date->format('d-m-Y') }}</td>
                                            <td>{{ $entry->document_number }}</td>
                                            <td>
                                                <div class="account-name">{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</div>
                                                @if($isUrdu ? $account->classification_ur : $account->classification_en)
                                                    <small class="muted">{{ $isUrdu ? $account->classification_ur : $account->classification_en }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ ($isUrdu ? ($entry->party?->name_ur ?: $entry->party?->name_en) : $entry->party?->name_en) ?: '-' }}
                                            </td>
                                            <td>{{ ($isUrdu ? ($entry->description_ur ?: $entry->description_en) : $entry->description_en) ?: '-' }}</td>
                                            <td class="amount">Rs. {{ number_format($entry->debit, 2) }}</td>
                                            <td class="amount">Rs. {{ number_format($entry->credit, 2) }}</td>
                                            <td class="amount">Rs. {{ number_format(in_array((int) $head->id, [1, 4], true) ? $entry->debit - $entry->credit : $entry->credit - $entry->debit, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="account-total">
                                        <td colspan="5">{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }} - @lang('messages.consolidated_total')</td>
                                        <td class="amount">Rs. {{ number_format($account->entries->sum('debit'), 2) }}</td>
                                        <td class="amount">Rs. {{ number_format($account->entries->sum('credit'), 2) }}</td>
                                        <td class="amount">
                                            Rs. {{ number_format(
                                                in_array((int) $head->id, [1, 4], true)
                                                    ? $account->entries->sum('debit') - $account->entries->sum('credit')
                                                    : $account->entries->sum('credit') - $account->entries->sum('debit'),
                                                2
                                            ) }}
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                            <tfoot>
                                <tr class="total">
                                    <td colspan="5">{{ $isUrdu ? ($head->name_ur ?: $head->name_en) : $head->name_en }} - @lang('messages.consolidated_total')</td>
                                    <td class="amount">Rs. {{ number_format($head->debit, 2) }}</td>
                                    <td class="amount">Rs. {{ number_format($head->credit, 2) }}</td>
                                    <td class="amount">Rs. {{ number_format($head->amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </section>
                @endif
            @endforeach
        </section>
    @empty
        <p class="empty">@lang('messages.no_financial_activity_data')</p>
    @endforelse

    <div class="footer">@lang('messages.generated_on') {{ now()->format('d M Y H:i') }}</div>
</main>
</body>
</html>
