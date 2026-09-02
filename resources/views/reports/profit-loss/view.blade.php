@extends('layouts.backend')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">@lang('messages.profit_loss_statement')</h2>
            <i class="fa fa-chart-line text-primary"></i>
        </div>
        <div class="card-body">
            <p class="text-muted">@lang('messages.profit_loss_period_hint')</p>
            <form action="{{ route('reports.profit.loss.report') }}" method="GET" target="_blank">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="from_date">@lang('messages.from_date') <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ old('from_date', request('from_date', now()->startOfYear()->format('Y-m-d'))) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="to_date">@lang('messages.to_date') <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ old('to_date', request('to_date', now()->format('Y-m-d'))) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="project_id">@lang('messages.project')</label>
                        <select class="form-control select2" id="project_id" name="project_id[]" multiple>
                            <option value="all" @selected(in_array('all', (array) request('project_id', [])))>@lang('messages.select_all_projects')</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(in_array($project->id, (array) request('project_id', [])))>{{ App::getLocale() === 'ur' ? ($project->name_ur ?: $project->name_en) : $project->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-file-invoice-dollar me-1"></i> @lang('messages.generate_report')</button>
                <a href="{{ route('reports.profit.loss.view') }}" class="btn btn-secondary">@lang('messages.reset')</a>
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
