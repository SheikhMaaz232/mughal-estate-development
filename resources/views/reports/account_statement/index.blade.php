@extends('layouts.backend')

@section('content')
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    @lang('messages.account_statement')
                </h4>
            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('reports.account-statement.report') }}">
                    <div class="row">
                        {{-- Account --}}
                        <div class="col-md-5">
                            <div class="form-group">

                                <label for="detail_account_id">
                                    @lang('messages.account')
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="detail_account_id[]" id="ledger_detail_account_id"
                                    class="form-control form-select" multiple></select>

                                @error('detail_account_id')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- From Date --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="from_date">
                                    @lang('messages.from_date')
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="from_date" id="from_date" class="form-control"
                                    value="{{ old('from_date', request('from_date', now()->startOfMonth()->format('Y-m-d'))) }}"
                                    required>
                                @error('from_date')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- To Date --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="to_date">
                                    @lang('messages.to_date')
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" name="to_date" id="to_date" class="form-control"
                                    value="{{ old('to_date', request('to_date', now()->format('Y-m-d'))) }}" required>

                                @error('to_date')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Button --}}
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>
                                    &nbsp;
                                </label>

                                <button type="submit" class="btn btn-primary form-control">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
