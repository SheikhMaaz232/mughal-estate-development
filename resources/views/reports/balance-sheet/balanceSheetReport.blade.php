<!DOCTYPE html>
<html lang="{{ $isUrdu ? 'ur' : 'en' }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('messages.balance_sheet')</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 20px;
            color: #1f2933;
            font: 14px/1.55 "Noto Nastaliq Urdu", "Noto Naskh Arabic", Arial, sans-serif;
            direction: {{ $isUrdu ? 'rtl' : 'ltr' }};
            text-align: {{ $isUrdu ? 'right' : 'left' }};
        }
        h2, h3, p { margin-top: 0; }
        .project { border: 1px solid #d6dce1; padding: 18px; margin-bottom: 24px; }
        .title, .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .title { font-size: 20px; font-weight: 700; margin-bottom: 14px; }
        .section-header { background: #eaf0f4; padding: 9px 12px; margin: 14px 0 6px; font-weight: 700; }
        .toggle-button {
            width: 27px; height: 27px; flex: 0 0 27px; border: 1px solid #66727d;
            border-radius: 4px; background: #fff; color: #263746; font-weight: 700; cursor: pointer;
        }
        .section-body.collapsed, .tree-children-row.collapsed { display: none !important; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #d6dce1; padding: 8px 10px; vertical-align: top; }
        th { background: #f3f6f8; }
        .tree-table { margin: 0; }
        .tree-children-row > td { padding: 0; border: 0; }
        .tree-children-row table { margin-bottom: 0; }
        .tree-name { font-weight: 600; }
        .tree-balance { text-align: {{ $isUrdu ? 'left' : 'right' }}; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .total { background: #e8f5e9; font-weight: 700; }
        .summary { margin-top: 42px; page-break-before: always; }
        .summary-project { margin: 16px 0; }
        .summary-grand { border-top: 3px solid #263746; margin-top: 26px; padding-top: 14px; }
        .muted { color: #66727d; }
        .print { margin-bottom: 16px; padding: 8px 14px; color: #fff; background: #1769aa; border: 0; cursor: pointer; }
        @media print {
            body { margin: 0; }
            .print { display: none; }
            .project { break-inside: auto; }
            tr, .title, .section-header { break-inside: avoid; }
            .summary { page-break-before: always; }
        }
    </style>
</head>
<body>
    <button class="print" onclick="window.print()">@lang('messages.print_report')</button>
    <h2>@lang('messages.company_name') - @lang('messages.balance_sheet')</h2>
    @if($asOfDate)
        <p>@lang('messages.as_of_date'): {{ $asOfDate->format('d-m-Y') }}</p>
    @endif

    @forelse($projectWiseData as $projectIndex => $project)
        <section class="project">
            <div class="title">
                <span>{{ $isUrdu ? ($project->project_name_ur ?: $project->project_name_en) : $project->project_name_en }}</span>
                <button type="button" class="toggle-button" data-target="#project-body-{{ $projectIndex }}"
                        aria-label="@lang('messages.toggle_project')" aria-expanded="true">-</button>
            </div>

            <div id="project-body-{{ $projectIndex }}" class="section-body">
                <table>
                    <thead>
                        <tr>
                            <th>@lang('messages.chart_of_accounts')</th>
                            <th class="tree-balance">@lang('messages.child_balance')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->chart as $headIndex => $head)
                            @include('reports.balance-sheet.tree-node', [
                                'node' => $head,
                                'treeId' => 'project-' . $projectIndex . '-head-' . $headIndex,
                                'depth' => 0,
                                'isUrdu' => $isUrdu,
                            ])
                        @endforeach
                    </tbody>
                </table>
                <table class="summary-project">
                    <tbody>
                        <tr><th>@lang('messages.total_assets')</th><td class="tree-balance">{{ number_format($project->total_assets, 2) }}</td></tr>
                        <tr><th>@lang('messages.total_liabilities')</th><td class="tree-balance">{{ number_format($project->total_liabilities, 2) }}</td></tr>
                        <tr><th>@lang('messages.total_income')</th><td class="tree-balance">{{ number_format($project->total_income, 2) }}</td></tr>
                        <tr><th>@lang('messages.total_expenses')</th><td class="tree-balance">{{ number_format($project->total_expenses, 2) }}</td></tr>
                        <tr><th>@lang('messages.net_profit')</th><td class="tree-balance">{{ number_format($project->net_profit, 2) }}</td></tr>
                        <tr class="total"><th>@lang('messages.total_equity')</th><td class="tree-balance">{{ number_format($project->total_equity, 2) }}</td></tr>
                        <tr><th>@lang('messages.liabilities_and_equity')</th><td class="tree-balance">{{ number_format($project->total_liabilities + $project->total_equity, 2) }}</td></tr>
                        <tr><th>@lang('messages.balance_difference')</th><td class="tree-balance">{{ number_format($project->total_assets - ($project->total_liabilities + $project->total_equity), 2) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    @empty
        <p class="muted">@lang('messages.no_balance_sheet_data')</p>
    @endforelse

    <section class="summary">
        <h2>@lang('messages.summary') — @lang('messages.all_projects')</h2>
        <table>
            <tbody>
                <tr><th>@lang('messages.total_assets')</th><td class="tree-balance">{{ number_format($grandAssets, 2) }}</td></tr>
                <tr><th>@lang('messages.total_liabilities')</th><td class="tree-balance">{{ number_format($grandLiabilities, 2) }}</td></tr>
                <tr><th>@lang('messages.total_income')</th><td class="tree-balance">{{ number_format($grandIncome, 2) }}</td></tr>
                <tr><th>@lang('messages.total_expenses')</th><td class="tree-balance">{{ number_format($grandExpenses, 2) }}</td></tr>
                <tr><th>@lang('messages.net_profit')</th><td class="tree-balance">{{ number_format($grandNetProfit, 2) }}</td></tr>
                <tr><th>@lang('messages.owner_equity')</th><td class="tree-balance">{{ number_format($grandOwnerEquity, 2) }}</td></tr>
                <tr class="total"><th>@lang('messages.total_equity')</th><td class="tree-balance">{{ number_format($grandEquity, 2) }}</td></tr>
                <tr><th>@lang('messages.liabilities_and_equity')</th><td class="tree-balance">{{ number_format($grandLiabilities + $grandEquity, 2) }}</td></tr>
                <tr><th>@lang('messages.balance_difference')</th><td class="tree-balance">{{ number_format($grandAssets - ($grandLiabilities + $grandEquity), 2) }}</td></tr>
            </tbody>
        </table>
    </section>
    <p class="muted">@lang('messages.generated_on') {{ now()->format('d M Y H:i') }}</p>

    <script>
        document.addEventListener('click', function (event) {
            const button = event.target.closest('.toggle-button');
            if (!button) return;

            const target = document.querySelector(button.dataset.target);
            if (!target) return;

            const collapsed = target.classList.toggle('collapsed');
            button.textContent = collapsed ? '+' : '-';
            button.setAttribute('aria-expanded', String(!collapsed));
        });
    </script>
</body>
</html>
