<!DOCTYPE html>
<html lang="{{ $isUrdu ? 'ur' : 'en' }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('messages.financial_position_report')</title>
    <style>
        :root { --ink: #17212b; --muted: #687580; --line: #dce3e8; --blue: #1769aa; --green: #16794c; --red: #b42318; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px; color: var(--ink); font: 14px/1.5 "Segoe UI", Tahoma, sans-serif; background: #f4f6f8; }
        .sheet { max-width: 1100px; margin: auto; padding: 42px 48px; background: #fff; box-shadow: 0 5px 25px rgba(23,33,43,.08); }
        .header { display: flex; justify-content: space-between; border-bottom: 3px solid var(--ink); padding-bottom: 20px; margin-bottom: 24px; }
        h1, h2, h3, p { margin-top: 0; } h1 { margin-bottom: 4px; font-size: 28px; } h2 { margin-bottom: 4px; font-size: 20px; } h3 { font-size: 16px; }
        .muted { color: var(--muted); } .period { text-align: {{ $isUrdu ? 'left' : 'right' }}; color: var(--muted); }
        .summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 28px; }
        .summary-card { padding: 16px; border: 1px solid var(--line); border-top: 3px solid var(--blue); } .summary-card.check { border-top-color: var(--green); } .summary-card.warning { border-top-color: var(--red); }
        .summary-label { color: var(--muted); font-size: 12px; } .summary-value { margin-top: 5px; font-size: 20px; font-weight: 700; }
        .project { margin: 28px 0 34px; page-break-inside: avoid; } .project-title { padding: 10px 14px; color: #fff; background: var(--ink); font-size: 17px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; } th, td { padding: 9px 12px; border-bottom: 1px solid var(--line); } th { color: var(--muted); background: #f7f9fa; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; } .amount { text-align: right; font-variant-numeric: tabular-nums; }
        [dir="rtl"] .amount { text-align: left; } .section-row td { padding-top: 18px; color: var(--blue); font-size: 15px; font-weight: 700; border-bottom: 2px solid var(--blue); } .total td { font-weight: 700; background: #f7f9fa; } .grand-total td { font-size: 16px; font-weight: 700; border-top: 3px solid var(--ink); }
        .check-row { margin: 30px 0; padding: 16px; border: 1px solid var(--line); border-left: 4px solid var(--green); } .check-row.warning { border-left-color: var(--red); } [dir="rtl"] .check-row { border-right: 4px solid var(--green); border-left: 1px solid var(--line); } [dir="rtl"] .check-row.warning { border-right-color: var(--red); }
        .print { display: inline-block; margin-bottom: 18px; padding: 8px 14px; color: #fff; background: var(--blue); border: 0; cursor: pointer; } .footer { margin-top: 36px; color: var(--muted); font-size: 11px; text-align: center; }
        @media print { body { padding: 0; background: #fff; } .sheet { padding: 20px; box-shadow: none; } .print { display: none; } } @media (max-width: 700px) { body { padding: 10px; } .sheet { padding: 20px; } .summary { grid-template-columns: repeat(2, 1fr); } .header { display: block; } .period { margin-top: 15px; text-align: {{ $isUrdu ? 'right' : 'left' }}; } }
    </style>
</head>
<body>
<div class="sheet">
    <button class="print" onclick="window.print()">@lang('messages.print_report')</button>
    <header class="header">
        <div><h1>@lang('messages.company_name')</h1><h2>@lang('messages.financial_position_report')</h2><p class="muted">@lang('messages.assets_liabilities_equity_report')</p></div>
        <div class="period"><strong>@lang('messages.as_of_date')</strong><br>{{ $asOfDate->format('d M Y') }}</div>
    </header>

    <section class="summary">
        <div class="summary-card"><div class="summary-label">@lang('messages.total_assets')</div><div class="summary-value">Rs. {{ number_format($totalAssets, 2) }}</div></div>
        <div class="summary-card"><div class="summary-label">@lang('messages.total_liabilities')</div><div class="summary-value">Rs. {{ number_format($totalLiabilities, 2) }}</div></div>
        <div class="summary-card"><div class="summary-label">@lang('messages.total_equity')</div><div class="summary-value">Rs. {{ number_format($totalEquity, 2) }}</div></div>
        <div class="summary-card {{ abs($difference) < 0.01 ? 'check' : 'warning' }}"><div class="summary-label">@lang('messages.balance_check')</div><div class="summary-value">{{ abs($difference) < 0.01 ? __('messages.balanced') : __('messages.out_of_balance') }}</div></div>
    </section>

    @forelse($projectWiseData as $project)
        <section class="project">
            <div class="project-title">{{ $isUrdu ? $project->project_name_ur : $project->project_name_en }}</div>
            <table>
                <thead><tr><th>@lang('messages.account_name')</th><th class="amount">@lang('messages.amount')</th></tr></thead>
                <tbody>
                    <tr class="section-row"><td colspan="2">@lang('messages.assets')</td></tr>
                    @forelse($project->assets as $account)<tr><td>{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</td><td class="amount">Rs. {{ number_format(max(0, $account->balance), 2) }}</td></tr>@empty<tr><td colspan="2" class="muted">@lang('messages.no_assets_found')</td></tr>@endforelse
                    <tr class="total"><td>@lang('messages.total_assets')</td><td class="amount">Rs. {{ number_format($project->total_assets, 2) }}</td></tr>
                    <tr class="section-row"><td colspan="2">@lang('messages.liabilities')</td></tr>
                    @forelse($project->liabilities as $account)<tr><td>{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</td><td class="amount">Rs. {{ number_format(max(0, -$account->balance), 2) }}</td></tr>@empty<tr><td colspan="2" class="muted">@lang('messages.no_liabilities_found')</td></tr>@endforelse
                    <tr class="total"><td>@lang('messages.total_liabilities')</td><td class="amount">Rs. {{ number_format($project->total_liabilities, 2) }}</td></tr>
                    <tr class="section-row"><td colspan="2">@lang('messages.equity')</td></tr>
                    @forelse($project->equity as $account)<tr><td>{{ $isUrdu ? ($account->name_ur ?: $account->name_en) : $account->name_en }}</td><td class="amount">Rs. {{ number_format(max(0, -$account->balance), 2) }}</td></tr>@empty<tr><td colspan="2" class="muted">@lang('messages.no_equity_found')</td></tr>@endforelse
                    <tr><td>@lang('messages.retained_earnings')</td><td class="amount">Rs. {{ number_format($project->retained_earnings, 2) }}</td></tr>
                    <tr class="total"><td>@lang('messages.total_equity')</td><td class="amount">Rs. {{ number_format($project->total_equity, 2) }}</td></tr>
                    <tr class="grand-total"><td>@lang('messages.liabilities_and_equity')</td><td class="amount">Rs. {{ number_format($project->liabilities_and_equity, 2) }}</td></tr>
                </tbody>
            </table>
        </section>
    @empty
        <p class="muted">@lang('messages.no_financial_position_data')</p>
    @endforelse

    <div class="check-row {{ abs($difference) < 0.01 ? '' : 'warning' }}"><strong>@lang('messages.balance_check'):</strong> {{ number_format($totalAssets, 2) }} = {{ number_format($liabilitiesAndEquity, 2) }} <span class="muted">(@lang('messages.difference'): {{ number_format(abs($difference), 2) }})</span></div>
    <section><h3>@lang('messages.consolidated_total')</h3><table><tr><td>@lang('messages.total_assets')</td><td class="amount">Rs. {{ number_format($totalAssets, 2) }}</td></tr><tr><td>@lang('messages.total_liabilities')</td><td class="amount">Rs. {{ number_format($totalLiabilities, 2) }}</td></tr><tr><td>@lang('messages.total_equity')</td><td class="amount">Rs. {{ number_format($totalEquity, 2) }}</td></tr><tr class="grand-total"><td>@lang('messages.liabilities_and_equity')</td><td class="amount">Rs. {{ number_format($liabilitiesAndEquity, 2) }}</td></tr></table></section>
    <div class="footer">@lang('messages.generated_on') {{ now()->format('d M Y H:i') }}</div>
</div>
</body>
</html>
