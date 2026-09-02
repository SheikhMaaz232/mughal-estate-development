<!DOCTYPE html>
<html lang="{{ $isUrdu ? 'ur' : 'en' }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('messages.profit_loss_statement')</title>
    <style>
        :root { --ink: #17212b; --muted: #66727d; --line: #dce2e7; --blue: #1769aa; --green: #16794c; --red: #b42318; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px; color: var(--ink); font: 14px/1.5 "Segoe UI", Tahoma, sans-serif; background: #f4f6f8; }
        .sheet { max-width: 1100px; margin: auto; padding: 42px 48px; background: #fff; box-shadow: 0 5px 25px rgba(23,33,43,.08); }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid var(--ink); padding-bottom: 20px; margin-bottom: 24px; }
        h1, h2, h3, p { margin-top: 0; } h1 { margin-bottom: 4px; font-size: 28px; } h2 { margin-bottom: 4px; font-size: 20px; } h3 { margin-bottom: 14px; font-size: 16px; }
        .muted { color: var(--muted); } .period { text-align: {{ $isUrdu ? 'left' : 'right' }}; color: var(--muted); }
        .summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 28px; }
        .summary-card { padding: 16px; border: 1px solid var(--line); border-top: 3px solid var(--blue); } .summary-card.profit { border-top-color: var(--green); }
        .summary-card.loss { border-top-color: var(--red); } .summary-label { color: var(--muted); font-size: 12px; } .summary-value { margin-top: 5px; font-size: 21px; font-weight: 700; }
        .project { margin: 28px 0 34px; page-break-inside: avoid; } .project-title { padding: 10px 14px; color: #fff; background: var(--ink); font-size: 17px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; } th, td { padding: 9px 12px; border-bottom: 1px solid var(--line); } th { color: var(--muted); background: #f7f9fa; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; } .amount { text-align: right; font-variant-numeric: tabular-nums; }
        [dir="rtl"] .amount { text-align: left; } .section-row td { padding-top: 18px; color: var(--blue); font-size: 15px; font-weight: 700; border-bottom: 2px solid var(--blue); } .total td { font-weight: 700; background: #f7f9fa; } .profit-row td { color: var(--green); font-size: 16px; font-weight: 700; border-top: 2px solid var(--green); }
        .loss-text { color: var(--red); } .grand { margin-top: 30px; padding-top: 18px; border-top: 3px solid var(--ink); } .footer { margin-top: 36px; color: var(--muted); font-size: 11px; text-align: center; }
        .print { display: inline-block; margin-bottom: 18px; padding: 8px 14px; color: #fff; background: var(--blue); border: 0; cursor: pointer; }
        @media print { body { padding: 0; background: #fff; } .sheet { padding: 20px; box-shadow: none; } .print { display: none; } }
        @media (max-width: 700px) { body { padding: 10px; } .sheet { padding: 20px; } .summary { grid-template-columns: 1fr; } .header { display: block; } .period { margin-top: 15px; text-align: {{ $isUrdu ? 'right' : 'left' }}; } }
    </style>
</head>
<body>
<div class="sheet">
    <button class="print" onclick="window.print()">@lang('messages.print_report')</button>
    <header class="header">
        <div><h1>@lang('messages.company_name')</h1><h2>@lang('messages.profit_loss_statement')</h2><p class="muted">@lang('messages.income_and_expenses_report')</p></div>
        <div class="period"><strong>@lang('messages.reporting_period')</strong><br>{{ $fromDate->format('d M Y') }} - {{ $toDate->format('d M Y') }}</div>
    </header>

    <section class="summary">
        <div class="summary-card"><div class="summary-label">@lang('messages.total_income')</div><div class="summary-value">Rs. {{ number_format($totalIncome, 2) }}</div></div>
        <div class="summary-card"><div class="summary-label">@lang('messages.total_expenses')</div><div class="summary-value">Rs. {{ number_format($totalExpenses, 2) }}</div></div>
        <div class="summary-card {{ $netProfit >= 0 ? 'profit' : 'loss' }}"><div class="summary-label">{{ $netProfit >= 0 ? __('messages.net_profit') : __('messages.net_loss') }}</div><div class="summary-value {{ $netProfit < 0 ? 'loss-text' : '' }}">Rs. {{ number_format(abs($netProfit), 2) }}</div></div>
    </section>

    @forelse($projectWiseData as $project)
        <section class="project">
            <div class="project-title">{{ $isUrdu ? $project->project_name_ur : $project->project_name_en }}</div>
            <table>
                <thead><tr><th>@lang('messages.account_name')</th><th class="amount">@lang('messages.amount')</th></tr></thead>
                <tbody>
                    <tr class="section-row"><td colspan="2">@lang('messages.income')</td></tr>
                    @forelse($project->income as $account)<tr><td>{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</td><td class="amount">Rs. {{ number_format($account->amount, 2) }}</td></tr>@empty<tr><td colspan="2" class="muted">@lang('messages.no_income_found')</td></tr>@endforelse
                    <tr class="total"><td>@lang('messages.total_income')</td><td class="amount">Rs. {{ number_format($project->total_income, 2) }}</td></tr>
                    <tr class="section-row"><td colspan="2">@lang('messages.expenses')</td></tr>
                    @forelse($project->expenses as $account)<tr><td>{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</td><td class="amount">Rs. {{ number_format($account->amount, 2) }}</td></tr>@empty<tr><td colspan="2" class="muted">@lang('messages.no_expenses_found')</td></tr>@endforelse
                    <tr class="total"><td>@lang('messages.total_expenses')</td><td class="amount">Rs. {{ number_format($project->total_expenses, 2) }}</td></tr>
                    <tr class="profit-row"><td>{{ $project->gross_profit >= 0 ? __('messages.net_profit') : __('messages.net_loss') }}</td><td class="amount">Rs. {{ number_format(abs($project->gross_profit), 2) }}</td></tr>
                </tbody>
            </table>
        </section>
    @empty
        <p class="muted">@lang('messages.no_profit_loss_data')</p>
    @endforelse

    <section class="grand">
        <h3>@lang('messages.consolidated_total')</h3>
        <table><tr><td>@lang('messages.total_income')</td><td class="amount">Rs. {{ number_format($totalIncome, 2) }}</td></tr><tr><td>@lang('messages.total_expenses')</td><td class="amount">Rs. {{ number_format($totalExpenses, 2) }}</td></tr><tr class="profit-row"><td>{{ $netProfit >= 0 ? __('messages.net_profit') : __('messages.net_loss') }}</td><td class="amount">Rs. {{ number_format(abs($netProfit), 2) }}</td></tr></table>
    </section>
    <div class="footer">@lang('messages.generated_on') {{ now()->format('d M Y H:i') }}</div>
</div>
</body>
</html>
