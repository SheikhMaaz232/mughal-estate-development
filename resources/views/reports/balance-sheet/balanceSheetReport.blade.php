<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>@lang('messages.balance_sheet')</title>

    <style>
        body {
            font-family: Arial;
            font-size: 14px;
            margin: 20px;
            direction: {{ $isUrdu ? 'rtl' : 'ltr' }};
            text-align: {{ $isUrdu ? 'right' : 'left' }};
        }

        .project {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            cursor: pointer;
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .toggle-button {
            width: 26px;
            height: 26px;
            border: 1px solid #555;
            border-radius: 4px;
            background: #fff;
            color: #333;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: 10px;
        }

        .section-body.collapsed {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f5f5f5;
        }

        .total {
            font-weight: bold;
            background: #e8f5e9;
        }

        .profit {
            font-weight: bold;
            background: #fff3cd;
        }

        .summary {
            margin-top: 40px;
        }

        .project-summary {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>@lang('messages.balance_sheet')</h2>

    @if ($asOfDate)
        <p>{{ $asOfDate->format('d-m-Y') }}</p>
    @endif

    @foreach ($projectWiseData as $projectIndex => $project)
        <div class="project">

            <div class="title">
                <div>{{ $isUrdu ? $project->project_name_ur : $project->project_name_en }}</div>
                <button type="button" class="toggle-button project-toggle" data-target="#project-body-{{ $projectIndex }}">-</button>
            </div>

            <div id="project-body-{{ $projectIndex }}" class="project-summary">

                {{-- ASSETS --}}
                <div class="section-header" data-target="#assets-{{ $projectIndex }}">
                    <span>@lang('messages.assets')</span>
                    <button type="button" class="toggle-button section-toggle" data-target="#assets-{{ $projectIndex }}">-</button>
                </div>
                <div id="assets-{{ $projectIndex }}" class="section-body">
                    <table>
                        @foreach ($project->assets as $a)
                            <tr>
                                <td>{{ $isUrdu ? $a->account_name_ur : $a->account_name_en }}</td>
                                <td>{{ number_format(abs($a->balance), 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total">
                            <td>@lang('messages.total_assets')</td>
                            <td>{{ number_format($project->total_assets, 2) }}</td>
                        </tr>
                    </table>
                </div>

                {{-- LIABILITIES --}}
                <div class="section-header" data-target="#liabilities-{{ $projectIndex }}">
                    <span>@lang('messages.liabilities')</span>
                    <button type="button" class="toggle-button section-toggle" data-target="#liabilities-{{ $projectIndex }}">-</button>
                </div>
                <div id="liabilities-{{ $projectIndex }}" class="section-body">
                    <table>
                        @foreach ($project->liabilities as $l)
                            <tr>
                                <td>{{ $isUrdu ? $l->account_name_ur : $l->account_name_en }}</td>
                                <td>{{ number_format(abs($l->balance), 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total">
                            <td>@lang('messages.total_liabilities')</td>
                            <td>{{ number_format($project->total_liabilities, 2) }}</td>
                        </tr>
                    </table>
                </div>

                {{-- EQUITY --}}
                <div class="section-header" data-target="#equity-{{ $projectIndex }}">
                    <span>@lang('messages.equity')</span>
                    <button type="button" class="toggle-button section-toggle" data-target="#equity-{{ $projectIndex }}">-</button>
                </div>
                <div id="equity-{{ $projectIndex }}" class="section-body">
                    <table>
                        @foreach ($project->equity as $e)
                            <tr>
                                <td>{{ $isUrdu ? $e->account_name_ur : $e->account_name_en }}</td>
                                <td>{{ number_format(abs($e->balance), 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="profit">
                            <td>@lang('messages.net_profit')</td>
                            <td>{{ number_format($project->net_profit, 2) }}</td>
                        </tr>
                        <tr class="total">
                            <td>@lang('messages.total_equity')</td>
                            <td>{{ number_format($project->total_equity, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <strong>
                    {{ number_format($project->total_assets, 2) }}
                    =
                    {{ number_format($project->total_liabilities + $project->total_equity, 2) }}
                </strong>

            </div>
        </div>
    @endforeach

    {{-- GRAND SUMMARY --}}
    <div class="summary">

        <h3>@lang('messages.summary')</h3>

        <table>
            <tr>
                <th>@lang('messages.total_assets')</th>
                <td>{{ number_format($grandAssets, 2) }}</td>
            </tr>

            <tr>
                <th>@lang('messages.total_liabilities')</th>
                <td>{{ number_format($grandLiabilities, 2) }}</td>
            </tr>

            <tr>
                <th>@lang('messages.net_profit')</th>
                <td>{{ number_format($grandNetProfit, 2) }}</td>
            </tr>

            <tr class="total">
                <th>@lang('messages.total_equity')</th>
                <td>{{ number_format($grandEquity, 2) }}</td>
            </tr>
        </table>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function toggleSection(button) {
                var target = button.dataset.target;
                var body = document.querySelector(target);
                if (!body) return;
                var expanded = !body.classList.contains('collapsed');
                body.classList.toggle('collapsed');
                button.textContent = expanded ? '+' : '-';
            }

            document.querySelectorAll('.project-toggle').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    var target = document.querySelector(button.dataset.target);
                    if (!target) return;
                    var isCollapsed = target.classList.toggle('collapsed');
                    button.textContent = isCollapsed ? '+' : '-';
                });
            });

            document.querySelectorAll('.section-toggle').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    var target = document.querySelector(button.dataset.target);
                    if (!target) return;
                    var isCollapsed = target.classList.toggle('collapsed');
                    button.textContent = isCollapsed ? '+' : '-';
                });
            });
        });
    </script>
</body>

</html>
