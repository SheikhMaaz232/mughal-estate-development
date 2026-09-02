@extends('layouts.backend')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">@lang('messages.financial_position_report')</h2>
            <i class="fa fa-scale-balanced text-primary"></i>
        </div>
        <div class="card-body">
            <p class="text-muted">@lang('messages.financial_position_hint')</p>
            <form action="{{ route('reports.financial.position.report') }}" method="GET" target="_blank">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="as_of_date">@lang('messages.as_of_date') <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="as_of_date" name="as_of_date" value="{{ old('as_of_date', request('as_of_date', now()->format('Y-m-d'))) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="project_id">@lang('messages.project')</label>
                        <select class="form-control select2" id="project_id" name="project_id[]" multiple>
                            <option value="all" @selected(in_array('all', (array) request('project_id', [])))>@lang('messages.select_all_projects')</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(in_array($project->id, (array) request('project_id', [])))>{{ App::getLocale() === 'ur' ? ($project->name_ur ?: $project->name_en) : $project->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-file-invoice me-1"></i> @lang('messages.generate_report')</button>
                <a href="{{ route('reports.financial.position.view') }}" class="btn btn-secondary">@lang('messages.reset')</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () { $('.select2').select2({ width: '100%' }); });
</script>
@endpush
@endsection
