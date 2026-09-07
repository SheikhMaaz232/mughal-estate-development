@extends('layouts.backend')

@section('content')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    {{ __('messages.sale_report') }}
                </h4>
            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('reports.sale-report') }}" target="_blank">

                    <div class="row">

                        {{-- From Date --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    {{ __('messages.from_date') }}
                                </label>

                                <input type="date" name="from_date" class="form-control" value="{{ old('from_date') }}">

                            </div>

                        </div>


                        {{-- To Date --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    {{ __('messages.to_date') }}
                                </label>

                                <input type="date" name="to_date" class="form-control" value="{{ old('to_date') }}">

                            </div>

                        </div>


                        {{-- Project --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    {{ __('messages.project') }}
                                </label>

                                <select name="project_id" class="form-control">

                                    <option value="">
                                        {{ __('messages.all_projects') }}
                                    </option>

                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">

                                            @if (app()->getLocale() == 'ur')
                                                {{ $project->name_ur }}
                                            @else
                                                {{ $project->name_en }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-12">

                            <button type="submit" class="btn btn-primary">

                                {{ __('messages.search') }}

                            </button>

                            <a href="{{ route('reports.sale-report.filter') }}" class="btn btn-secondary">

                                {{ __('messages.reset') }}

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
