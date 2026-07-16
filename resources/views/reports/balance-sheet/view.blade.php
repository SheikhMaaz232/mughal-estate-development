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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="as_of_date">@lang('messages.as_of_date')</label>
                            <input type="text" class="form-control" id="as_of_date" name="as_of_date"
                                   placeholder="DD-MM-YYYY"
                                   value="{{ $request->as_of_date ?? now()->format('d-m-Y') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="project_id">@lang('messages.project')</label>
                            <select class="form-control select2" id="project_id" name="project_id[]" multiple="multiple">
                                <option value="all" @selected(in_array('all', (array) ($request->project_id ?? [])))>
                                    @lang('messages.select_all_projects')
                                </option>
                                @forelse($projects as $project)
                                    <option value="{{ $project->id }}"
                                        @selected(in_array($project->id, (array) ($request->project_id ?? [])))>
                                        {{ $project->name_en }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
@endsection
