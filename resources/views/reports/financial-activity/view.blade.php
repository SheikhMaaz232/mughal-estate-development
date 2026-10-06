@extends('layouts.backend')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">@lang('messages.financial_activity_report')</h2>
            <i class="fa fa-chart-pie text-primary"></i>
        </div>
        <div class="card-body">
            <p class="text-muted">@lang('messages.financial_activity_hint')</p>
            <form action="{{ route('reports.financial.activity.report') }}" method="GET" target="_blank">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="from_date">@lang('messages.from_date')</label>
                        <input type="date" class="form-control" id="from_date" name="from_date"
                               value="{{ request('from_date', now()->startOfYear()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="to_date">@lang('messages.to_date')</label>
                        <input type="date" class="form-control" id="to_date" name="to_date"
                               value="{{ request('to_date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="project_id">@lang('messages.project')</label>
                        <select class="form-control select2" id="project_id" name="project_id[]" multiple>
                            <option value="all" @selected(in_array('all', (array) request('project_id', [])))>
                                @lang('messages.select_all_projects')
                            </option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    @selected(in_array((string) $project->id, array_map('strval', (array) request('project_id', []))))>
                                    {{ app()->getLocale() === 'ur' ? ($project->name_ur ?: $project->name_en) : $project->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="main_head_id">@lang('messages.main_head')</label>
                        <select class="form-control" id="main_head_id" name="main_head_id">
                            <option value="">@lang('messages.all_main_heads')</option>
                            @foreach($mainHeads as $mainHead)
                                <option value="{{ $mainHead->id }}" @selected((string) request('main_head_id') === (string) $mainHead->id)>
                                    {{ app()->getLocale() === 'ur' ? ($mainHead->name_ur ?: $mainHead->name_en) : $mainHead->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-file-invoice-dollar me-1"></i> @lang('messages.generate_report')
                </button>
                <a href="{{ route('reports.financial.activity.view') }}" class="btn btn-secondary">@lang('messages.reset')</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({ width: '100%' });
    });
</script>
@endpush
@endsection
