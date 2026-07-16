@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('menu.bill-aging')</h2>
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

        <form action="{{ route('reports.bill.aging.report') }}" method="get" id="form-search" target="_blank">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id[]" id="project_id" class="form-control select2 form-select" multiple>
                        <option value="all">@lang('messages.select_all_projects')</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ collect(request('project_id'))->contains($project->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.party_ledger')</label>
                    <select name="party_id[]" id="party_id" class="form-control select2 form-select" multiple>
                        <option value="all">@lang('messages.select_all_parties')</option>
                        @foreach ($parties as $party)
                            <option value="{{ $party->id }}"
                                {{ collect(request('party_id'))->contains($party->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $party->name_ur ?? '-' : $party->name_en ?? '-' }}
                                -
                                ({{ App::getLocale() === 'ur' ? 'ذات' : 'CAST' }}:
                                {{ App::getLocale() === 'ur' ? $party->cast->title_ur ?? '-' : $party->cast->title_en ?? '-' }})
                                ({{ App::getLocale() === 'ur' ? 'شناختی کارڈ' : 'CNIC' }}:
                                {{ $party->cnic_no ?? 'N/A' }})
                                ({{ App::getLocale() === 'ur' ? 'فون' : 'Phone' }}:
                                {{ $party->contact_number_1 ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="as_of_date">@lang('messages.as_of_date')</label>
                    <input id="as_of_date" name="as_of_date" style="color: black;" class="form-control" type="text"
                        value="{{ request('as_of_date') }}" placeholder="DD-MM-YYYY">
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="report_type">@lang('messages.report_type')</label>
                    <select id="report_type" name="report_type" class="form-control form-select">
                        <option value="all" {{ request('report_type') === 'all' ? 'selected' : '' }}>@lang('messages.all')
                        </option>
                        <option value="receivable" {{ request('report_type') === 'receivable' ? 'selected' : '' }}>
                            @lang('messages.receivable')</option>
                        <option value="payable" {{ request('report_type') === 'payable' ? 'selected' : '' }}>
                            @lang('messages.payable')</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>
                    @if (request()->hasAny(['project_id', 'party_id', 'as_of_date', 'report_type']))
                        <a href="{{ route('reports.bill.aging.view') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('as_of_date');
            input.addEventListener('input', function() {
                let raw = this.value.replace(/\D/g, '');
                if (raw.length > 8) raw = raw.slice(0, 8);
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
            $('#party_id').select2({
                placeholder: "Select Parties"
            });

            $('#project_id').on('change', function() {
                let values = $(this).val();
                if (values && values.includes('all')) {
                    let allValues = [];
                    $('#project_id option').each(function() {
                        if ($(this).val() !== 'all') {
                            allValues.push($(this).val());
                        }
                    });
                    $('#project_id').val(allValues).trigger('change');
                }
            });

            $('#party_id').on('change', function() {
                let values = $(this).val();
                if (values && values.includes('all')) {
                    let allValues = [];
                    $('#party_id option').each(function() {
                        if ($(this).val() !== 'all') {
                            allValues.push($(this).val());
                        }
                    });
                    $('#party_id').val(allValues).trigger('change');
                }
            });
        });
    </script>
@endsection
