@extends('layouts.backend')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('messages.accounts_tree') }}</h1>
        <p class="text-muted">{{ __('messages.english') }} / {{ __('messages.urdu') }}</p>

        <style>
            .tree {
                padding: 20px;
                font-size: 14px;
            }

            .tree ul {
                list-style-type: none;
                padding-left: 20px;
                margin: 0;
            }

            .tree li {
                margin: 6px 0;
                line-height: 1.6;
            }

            .node-wrapper {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 4px 0;
            }

            .toggle-btn {
                cursor: pointer;
                width: 24px;
                height: 24px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: #fff;
                user-select: none;
                border: none;
                border-radius: 3px;
                font-size: 14px;
                background-color: #007bff;
                transition: background-color 0.2s;
                flex-shrink: 0;
                padding: 0;
            }

            .toggle-btn:hover {
                background-color: #0056b3;
            }

            .toggle-btn:active {
                transform: scale(0.95);
            }

            .children-list {
                display: block;
                animation: slideDown 0.2s ease-out;
            }

            .children-list.collapsed {
                display: none;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .node-label {
                padding: 4px 8px;
                border-radius: 3px;
                transition: background-color 0.2s;
            }

            .node-label:hover {
                background-color: #f0f0f0;
            }

            .urdu {
                font-family: 'Noto Nastaliq Urdu', serif;
                font-size: 16px;
            }

            .no-children {
                width: 24px;
                display: inline-block;
            }

            @media print {

button,
.pagination,
form {

    display:none !important;

}

body{

    margin:0;

    padding:10px;

}

}
        </style>
        <div class="row mb-3">

            <div class="col-md-4">

                <form id="filterForm" method="GET">

                    <select name="project_id" class="form-control">

                        <option value=""> @lang('messages.all-projects')</option>

                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}

                            </option>
                        @endforeach

                    </select>

            </div>

            <div class="col-md-8">

                <button class="btn btn-primary">

                    <i class="fa fa-search"></i>

                     @lang('messages.filter')

                </button>

                <a href="{{ route('detail-accounts.tree') }}" class="btn btn-secondary">

                     @lang('messages.reset')

                </a>

                @if (request()->has('project_id'))
                   <button type="button" class="btn btn-info" onclick="expandTree()">
    <i class="fa fa-plus-square"></i>  @lang('messages.expand-all')
</button>

<button type="button" class="btn btn-warning" onclick="collapseTree()">
    <i class="fa fa-minus-square"></i>  @lang('messages.collapse-all')
</button>

<button type="button" class="btn btn-success" onclick="printTree()">
    <i class="fa fa-print"></i>  @lang('messages.print')
</button>
                @endif

                </form>

            </div>

        </div>
        <div
    id="loading"
    style="display:none;text-align:center;padding:20px;">

    <div class="spinner-border text-primary"></div>

    <br>

     @lang('messages.loading')

</div>
@if(count($accountsTree))
<div id="printArea">

    <div class="tree">
            <ul>
                @foreach ($accountsTree as $mh)
                    <li>
                        <div class="node-wrapper">
                            @if (!empty($mh['control_heads']))
                                <button class="toggle-btn"
        onclick="toggleChildren('mh{{ $mh['id'] }}', this)">
    +
</button>
                            @else
                                <span class="no-children"></span>
                            @endif
                            <span class="node-label">
                                <strong>
                                    @if (app()->getLocale() == 'ur')
                                        <span class="urdu">{{ $mh['name_ur'] ?? '' }}</span>
                                    @else
                                        {{ $mh['name_en'] ?? '' }}
                                    @endif
                                </strong>
                            </span>
                        </div>

                        @if (!empty($mh['control_heads']))
                            <ul id="mh{{ $mh['id'] }}" class="children-list">
                                @foreach ($mh['control_heads'] as $ch)
                                    <li>
                                        <div class="node-wrapper">
                                            @if (!empty($ch['sub_heads']))
                                                <button class="toggle-btn"
        onclick="toggleChildren('ch{{ $ch['id'] }}', this)">
    +
</button>
                                            @else
                                                <span class="no-children"></span>
                                            @endif
                                            <span class="node-label">
                                                @if (app()->getLocale() == 'ur')
                                                    <span class="urdu">{{ $ch['name_ur'] ?? '' }}</span>
                                                @else
                                                    {{ $ch['name_en'] ?? '' }}
                                                @endif
                                            </span>
                                        </div>

                                        @if (!empty($ch['sub_heads']))
                                            <ul id="ch{{ $ch['id'] }}" class="children-list">
                                                @foreach ($ch['sub_heads'] as $sh)
                                                    <li>
                                                        <div class="node-wrapper">
                                                            @if (!empty($sh['sub_sub_heads']))
                                                                <button class="toggle-btn"
        onclick="toggleChildren('sh{{ $sh['id'] }}', this)">
    +
</button>
                                                            @else
                                                                <span class="no-children"></span>
                                                            @endif
                                                            <span class="node-label">
                                                                @if (app()->getLocale() == 'ur')
                                                                    <span class="urdu">{{ $sh['name_ur'] ?? '' }}</span>
                                                                @else
                                                                    {{ $sh['name_en'] ?? '' }}
                                                                @endif
                                                            </span>
                                                        </div>

                                                        @if (!empty($sh['sub_sub_heads']))
                                                            <ul id="sh{{ $sh['id'] }}" class="children-list">
                                                                @foreach ($sh['sub_sub_heads'] as $ssh)
                                                                    <li>
                                                                        @if ($ssh['name_en'] || $ssh['name_ur'])
                                                                            <div class="node-wrapper">
                                                                                @if (!empty($ssh['sub_sub_sub_heads']))
                                                                                   <button class="toggle-btn"
        onclick="toggleChildren('ssh{{ $ssh['id'] }}', this)">
    +
</button>
                                                                                @else
                                                                                    <span class="no-children"></span>
                                                                                @endif
                                                                                <span class="node-label">
                                                                                    @if (app()->getLocale() == 'ur')
                                                                                        <span
                                                                                            class="urdu">{{ $ssh['name_ur'] ?? '' }}</span>
                                                                                    @else
                                                                                        {{ $ssh['name_en'] ?? '' }}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @endif

                                                                        @if (!empty($ssh['sub_sub_sub_heads']))
                                                                            <ul id="ssh{{ $ssh['id'] }}" class="children-list">
                                                                                @foreach ($ssh['sub_sub_sub_heads'] as $sssh)
                                                                                    <li>
                                                                                        @if ($sssh['name_en'] || $sssh['name_ur'])
                                                                                            <div class="node-wrapper">
                                                                                                @if (!empty($sssh['detail_accounts']))
                                                                                                    <button class="toggle-btn"
        onclick="toggleChildren('sssh{{ $sssh['id'] }}', this)">
    +
</button>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="no-children"></span>
                                                                                                @endif
                                                                                                <span class="node-label">
                                                                                                    @if (app()->getLocale() == 'ur')
                                                                                                        <span
                                                                                                            class="urdu">{{ $sssh['name_ur'] ?? '' }}</span>
                                                                                                    @else
                                                                                                        {{ $sssh['name_en'] ?? '' }}
                                                                                                    @endif
                                                                                                </span>
                                                                                            </div>
                                                                                        @endif

                                                                                        @if (!empty($sssh['detail_accounts']))
                                                                                            <ul id="sssh{{ $sssh['id'] }}" class="children-list">
                                                                                                @foreach ($sssh['detail_accounts'] as $da)
                                                                                                    <li>
                                                                                                        <div
                                                                                                            class="node-wrapper">
                                                                                                            <span
                                                                                                                class="no-children"></span>
                                                                                                            <span
                                                                                                                class="node-label">
                                                                                                                @if (app()->getLocale() == 'ur')
                                                                                                                    <span
                                                                                                                        class="urdu">{{ $da['name_ur'] }}</span>
                                                                                                                @else
                                                                                                                    {{ $da['name_en'] }}
                                                                                                                @endif
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif

                                                                        @if (!empty($ssh['detail_accounts']))
                                                                            <ul class="children-list">
                                                                                @foreach ($ssh['detail_accounts'] as $da_list)
                                                                                    @if (!empty($da_list['detail_accounts']))
                                                                                        @foreach ($da_list['detail_accounts'] as $da)
                                                                                            <li>
                                                                                                <div class="node-wrapper">
                                                                                                    <span
                                                                                                        class="no-children"></span>
                                                                                                    <span
                                                                                                        class="node-label">
                                                                                                        @if (app()->getLocale() == 'ur')
                                                                                                            <span
                                                                                                                class="urdu">{{ $da['name_ur'] }}</span>
                                                                                                        @else
                                                                                                            {{ $da['name_en'] }}
                                                                                                        @endif
                                                                                                    </span>
                                                                                                </div>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    @else
                                                                                        @if (!empty($da_list['name_en']))
                                                                                            <li>
                                                                                                <div class="node-wrapper">
                                                                                                    <span
                                                                                                        class="no-children"></span>
                                                                                                    <span
                                                                                                        class="node-label">
                                                                                                        @if (app()->getLocale() == 'ur')
                                                                                                            <span
                                                                                                                class="urdu">{{ $da_list['name_ur'] }}</span>
                                                                                                        @else
                                                                                                            {{ $da_list['name_en'] }}
                                                                                                        @endif
                                                                                                    </span>
                                                                                                </div>
                                                                                            </li>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

</div>
@else

<div class="alert alert-warning">

   @lang('messages.no_records_found')

</div>

@endif
       
        <div class="mt-4">
            @if($mainHeadsTree)

<div class="mt-4">

{{ $mainHeadsTree->appends(request()->query())->links() }}

</div>

@endif
        </div>
    </div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===========================
    // Show Loading Spinner
    // ===========================
    const filterForm = document.getElementById("filterForm");

    if (filterForm) {
        filterForm.addEventListener("submit", function () {

            let loading = document.getElementById("loading");

            if (loading) {
                loading.style.display = "block";
            }

        });
    }

    // ===========================
    // Collapse all tree nodes initially
    // ===========================
    document.querySelectorAll(".children-list").forEach(function (ul) {
        ul.style.display = "none";
    });

    // Reset all buttons to "+"
    document.querySelectorAll(".toggle-btn").forEach(function (btn) {
        btn.innerHTML = "+";
    });

});


// ==========================================
// Expand / Collapse Single Node
// ==========================================
function toggleChildren(id, button) {
    let child = document.getElementById(id);

    if (!child) return;

    if (child.style.display === "none" || child.style.display === "") {
        child.style.display = "block";
        button.innerHTML = "-";
    } else {
        child.style.display = "none";
        button.innerHTML = "+";
    }
}


// ==========================================
// Expand All
// ==========================================
function expandAll() {

    document.querySelectorAll(".children-list").forEach(function (ul) {
        ul.style.display = "block";
    });

    document.querySelectorAll(".toggle-btn").forEach(function (btn) {
        btn.innerHTML = "-";
    });

}

function expandTree() {
    expandAll();
}


// ==========================================
// Collapse All
// ==========================================
function collapseAll() {

    document.querySelectorAll(".children-list").forEach(function (ul) {
        ul.style.display = "none";
    });

    document.querySelectorAll(".toggle-btn").forEach(function (btn) {
        btn.innerHTML = "+";
    });

}

function collapseTree() {
    collapseAll();
}


// ==========================================
// Print Tree
// ==========================================
function printTree() {

    expandAll();

    let printArea = document.getElementById("printArea");

    let popup = window.open("", "_blank", "width=1200,height=800");

    popup.document.write(`
        <html>
        <head>

            <title>Account Tree</title>

            <style>

                body{
                    font-family: Arial, sans-serif;
                    margin:20px;
                }

                ul{
                    list-style:none;
                    padding-left:20px;
                }

                li{
                    margin:5px 0;
                }

                .toggle-btn{
                    display:none;
                }

            </style>

        </head>

        <body>

            <h2 style="text-align:center;">
                Account Tree
            </h2>

            ${printArea.innerHTML}

        </body>

        </html>
    `);

    popup.document.close();

    popup.focus();

    setTimeout(function () {

        popup.print();

        popup.close();

        collapseAll();

    }, 500);

}
</script>
@endsection
