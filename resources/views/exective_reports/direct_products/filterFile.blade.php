@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    @lang('messages.generate-direct-products-report')
                </h3>
            </div>

            <div class="card-body">
                <form action="{{ route('exective-reports.direct-products.report') }}" method="GET" target="_blank">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <label>@lang('messages.projects')</label>
                            <select name="project_id[]" multiple class="form-control select2 custom-select2">
                                <option value="all">@lang('messages.all')</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">
                                        {{ app()->getLocale() == 'ur' ? $project->name_ur : $project->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary">@lang('messages.generate_report')</button>
                    <a href="{{ route('exective-reports.direct-products.filter') }}" class="btn btn-secondary">@lang('messages.reset')</a>
                </form>
            </div>
        </div>
    </div>
@endsection
