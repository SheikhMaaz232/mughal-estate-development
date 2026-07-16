<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}">

<head>

    <meta charset="utf-8">

    <title>

        Available Plots Report

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
            padding: 6px;
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

<script>
    function toggleProject(id, btn) {
        let section = document.getElementById(id);

        if (section.style.display === "none") {
            section.style.display = "block";
            btn.innerHTML = "-";
        } else {
            section.style.display = "none";
            btn.innerHTML = "+";
        }
    }
</script>

<body>

    @php
        $isUrdu = app()->getLocale() == 'ur';
    @endphp

    <h2 style="text-align:center">

        {{ $isUrdu ? 'دستیاب پلاٹوں کی رپورٹ' : 'Available Plots Report' }}

    </h2>

    @foreach ($groupedProjects as $projectId => $projectProducts)
        @php
            $projectName = $isUrdu
                ? $projectProducts->first()->project->name_ur
                : $projectProducts->first()->project->name_en;
        @endphp

        <div class="project-header"
            style="background:#dfe6e9;padding:10px;margin-top:20px;display:flex;justify-content:space-between;align-items:center;">

            <strong>{{ $projectName }}</strong>

            <button type="button" onclick="toggleProject('project-{{ $projectId }}', this)"
                style="padding:3px 10px;cursor:pointer;">
                +
            </button>

        </div>

        <div id="project-{{ $projectId }}" style="display:none;">

            <table>

                <thead>
                    <tr>
                        <th style="width: 50%">@lang('messages.plot')</th>
                        <th style="width: 25%">@lang('messages.status')</th>
                        <th style="width: 25%">@lang('messages.marla')</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($projectProducts as $product)
                        <tr>
                            <td>
                                {{ $isUrdu ? $product->name_ur : $product->name_en }}
                            </td>
                            <td style="text-align: center;">
                                @if ($product->status === 'Unverified')
                                    @lang('messages.unverified')
                                @elseif ($product->status === 'Verified')
                                    @lang('messages.verified')
                                @else
                                    -
                                @endif
                            </td>

                            <td style="text-align: center;">{{ number_format($product->total_marla, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="total-row">
                        <td colspan="2">
                            @lang('messages.project_total')
                        </td>

                        <td>
                            {{ number_format($projectProducts->sum('total_marla'), 2) }}
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>
    @endforeach

    <h3>@lang('messages.project_summary')</h3>

    <table>
        <thead>
            <tr>
                <th>
                    @lang('messages.project')
                </th>

                <th>
                    @lang('messages.total_marla')
                </th>
            </tr>
        </thead>

        <tbody>

            @foreach ($groupedProjects as $projectId => $projectProducts)
                <tr>

                    <td>
                        {{ $isUrdu ? $projectProducts->first()->project->name_ur : $projectProducts->first()->project->name_en }}
                    </td>

                    <td>
                        {{ number_format($projectProducts->sum('total_marla'), 2) }}
                    </td>

                </tr>
            @endforeach

            <tr class="total-row">

                <td>
                    <strong>
                        @lang('messages.grand_total_marla')
                    </strong>
                </td>

                <td>
                    <strong>
                        {{ number_format($grandTotalMarla, 2) }}
                    </strong>
                </td>

            </tr>

        </tbody>

    </table>

</body>

</html>
