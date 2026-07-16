@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.party_ledger')</h2>
                </div>

            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form action="{{ route('reports.recovery.sheet.report') }}" method="get" id="form-search" target="_blank">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="from_date">@lang('messages.from_date')</label>
                    <input id="from_date" name="from_date" style="color: black; " class="form-control" type="text"
                        value="{{ @$request['from_date'] }}" placeholder="@lang('messages.from_date')">
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="to_date">@lang('messages.to_date')</label>
                    <input id="to_date" type="text" name="to_date" class="form-control"
                        value="{{ @$request['to_date'] }}" placeholder="@lang('messages.to_date')" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id[]" id="project_id"
                        class="form-control select2 form-select @error('project_id') is-invalid @enderror" multiple>
                        <option value="all">@lang('messages.select_all_projects')</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ collect(request('project_id'))->contains($project->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['from_date', 'to_date', 'project_id']))
                        <a href="{{ route('reports.recovery.sheet.view') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('from_date'); // Change to your input's actual ID

            input.addEventListener('input', function() {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 8 digits max (DDMMYYYY)
                if (raw.length > 8) raw = raw.slice(0, 8);

                // Format as DD-MM-YYYY
                if (raw.length > 4) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2, 4) + '-' + raw.slice(4);
                } else if (raw.length > 2) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2);
                } else {
                    this.value = raw;
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('to_date'); // Change to your input's actual ID

            input.addEventListener('input', function() {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 8 digits max (DDMMYYYY)
                if (raw.length > 8) raw = raw.slice(0, 8);

                // Format as DD-MM-YYYY
                if (raw.length > 4) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2, 4) + '-' + raw.slice(4);
                } else if (raw.length > 2) {
                    this.value = raw.slice(0, 2) + '-' + raw.slice(2);
                } else {
                    this.value = raw;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#project_id').select2({
                placeholder: "Select Projects"
            });

            $('#project_id').on('change', function() {
                let values = $(this).val();

                if (values && values.includes('all')) {
                    // Select all real project options
                    let allValues = [];
                    $('#project_id option').each(function() {
                        if ($(this).val() !== 'all') {
                            allValues.push($(this).val());
                        }
                    });

                    $('#project_id').val(allValues).trigger('change');
                }
            });
        });
    </script>
@endsection
