@extends('layouts.backend')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h2>@lang('messages.balance_sheet')</h2>
        </div>

        <div class="card-body">
            <form action="{{ route('reports.balance.sheet.report') }}" method="GET" target="_blank">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="as_of_date">@lang('messages.as_of_date')</label>
                            <input type="text" class="form-control" id="as_of_date" name="as_of_date"
                                   placeholder="DD-MM-YYYY"
                                   value="{{ $request->as_of_date ?? now()->format('d-m-Y') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="project_id">@lang('messages.project')</label>
                            <select class="form-control select2" id="project_id" name="project_id[]" multiple="multiple">
                                <option value="all" @selected(in_array('all', (array) ($request->project_id ?? [])))>
                                    @lang('messages.select_all_projects')
                                </option>
                                @forelse($projects as $project)
                                    <option value="{{ $project->id }}"
                                        @selected(in_array((string) $project->id, array_map('strval', (array) ($request->project_id ?? []))))>
                                        {{ app()->getLocale() === 'ur' ? ($project->name_ur ?: $project->name_en) : $project->name_en }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bs_main_head_id">@lang('messages.main_head')</label>
                            <select class="form-control select2 chart-parent-filter" id="bs_main_head_id" name="main_head_id[]"
                                    multiple data-placeholder="@lang('messages.all_main_heads')">
                                @foreach($mainHeads as $mainHead)
                                    <option value="{{ $mainHead->id }}" @selected(in_array((string) $mainHead->id, array_map('strval', (array) $request->input('main_head_id', []))))>
                                        {{ app()->getLocale() === 'ur' ? ($mainHead->name_ur ?: $mainHead->name_en) : $mainHead->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @foreach([
                        'control_head_id' => 'control_head',
                        'sub_head_id' => 'sub_head',
                        'sub_sub_head_id' => 'sub_sub_head',
                        'sub_sub_sub_head_id' => 'sub_sub_sub_head',
                        'detail_account_id' => 'detail_account',
                    ] as $field => $label)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bs_{{ $field }}">{{ __("messages.$label") }}</label>
                                <select class="form-control select2 chart-filter" id="bs_{{ $field }}" name="{{ $field }}[]"
                                        data-level="{{ str_replace('_id', 's', $field) }}"
                                        data-parent="{{ [
                                            'control_head_id' => 'bs_main_head_id',
                                            'sub_head_id' => 'bs_control_head_id',
                                            'sub_sub_head_id' => 'bs_sub_head_id',
                                            'sub_sub_sub_head_id' => 'bs_sub_sub_head_id',
                                            'detail_account_id' => 'bs_sub_sub_sub_head_id',
                                        ][$field] }}"
                                        multiple data-placeholder="{{ __('messages.select-option') }}" disabled>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-12">
                        <div id="chart-filter-error" class="alert alert-danger d-none" role="alert">
                            {{ __('messages.chart_filter_error') }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">@lang('messages.generate_report')</button>
                        <a href="{{ route('reports.balance.sheet.view') }}" class="btn btn-secondary">@lang('messages.reset')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        const levels = [
            { parent: 'bs_main_head_id', child: 'bs_control_head_id', field: 'control_head_id', level: 'control_heads' },
            { parent: 'bs_control_head_id', child: 'bs_sub_head_id', field: 'sub_head_id', level: 'sub_heads' },
            { parent: 'bs_sub_head_id', child: 'bs_sub_sub_head_id', field: 'sub_sub_head_id', level: 'sub_sub_heads' },
            { parent: 'bs_sub_sub_head_id', child: 'bs_sub_sub_sub_head_id', field: 'sub_sub_sub_head_id', level: 'sub_sub_sub_heads' },
            { parent: 'bs_sub_sub_sub_head_id', child: 'bs_detail_account_id', field: 'detail_account_id', level: 'detail_accounts' },
        ];
        const isUrdu = @json(app()->getLocale() === 'ur');
        const optionsUrl = @json(route('reports.balance.sheet.chart-options'));
        const filterError = document.getElementById('chart-filter-error');

        async function loadOptions(level, parentId, selectedValue = '') {
            const child = document.getElementById(level.child);
            child.replaceChildren();
            const parentIds = Array.isArray(parentId) ? parentId.filter(Boolean) : (parentId ? [parentId] : []);
            child.disabled = parentIds.length === 0;

            if (parentIds.length === 0) {
                $(child).trigger('change.select2');
                return;
            }

            const url = new URL(optionsUrl, window.location.origin);
            url.searchParams.set('level', level.level);
            parentIds.forEach(function(id) {
                url.searchParams.append('parent_ids[]', id);
            });
            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) {
                throw new Error('Unable to load chart-of-account options.');
            }

            const options = await response.json();
            filterError.classList.add('d-none');
            const selectedIds = Array.isArray(selectedValue) ? selectedValue.map(String) : [String(selectedValue)];
            options.forEach(function(option) {
                const label = isUrdu ? (option.name_ur || option.name_en) : option.name_en;
                child.add(new Option(label, option.id, false, selectedIds.includes(String(option.id))));
            });
            child.disabled = options.length === 0;
            $(child).trigger('change.select2');
        }

        levels.forEach(function(level, index) {
            $('#' + level.parent).on('change', async function() {
                try {
                    for (let childIndex = index; childIndex < levels.length; childIndex++) {
                        const child = document.getElementById(levels[childIndex].child);
                        child.replaceChildren();
                        child.disabled = true;
                        $(child).trigger('change.select2');
                    }
                    await loadOptions(levels[index], $('#' + level.parent).val());
                } catch (error) {
                    filterError.classList.remove('d-none');
                    console.error(error);
                }
            });
        });

        (async function restoreSelectedFilters() {
            const selectedFilters = @json($selectedChartFilters);
            for (const level of levels) {
                const parentId = $('#' + level.parent).val();
                await loadOptions(level, parentId, selectedFilters[level.field] || '');
                if (selectedFilters[level.field]) {
                    $('#' + level.child).val(selectedFilters[level.field]).trigger('change.select2');
                }
            }
        })().catch(function(error) {
            filterError.classList.remove('d-none');
            console.error(error);
        });
    });
</script>
@endsection
@endsection
