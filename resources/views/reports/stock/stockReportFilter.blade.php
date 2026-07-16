@extends('layouts.backend')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                @lang('messages.generate-stock-report')
            </h3>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('reports.stock-report') }}">

                <div class="row">

                    {{-- Project --}}

                    <div class="col-md-3">

                        <label>@lang('messages.project')</label>

                        <select name="project_id"
                                class="form-control select2 custom-select2">

                            <option value="all">
                                 @lang('messages.all-projects')
                            </option>

                            @foreach($projects as $project)

                                <option value="{{ $project->id }}">
                                    {{ app()->getLocale() == 'ur'
                                        ? $project->name_ur
                                        : $project->name_en }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Product --}}

                    <div class="col-md-3">

                        <label>@lang('messages.product')</label>

                        <select name="product_id"
                                class="form-control select2 custom-select2">

                            <option value="all">
                                @lang('messages.all-products')
                            </option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}">
                                    {{ app()->getLocale() == 'ur'
                                        ? $product->name_ur
                                        : $product->name_en }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Date From --}}

                    <div class="col-md-2">

                        <label>@lang('messages.date-from')</label>

                        <input type="date"
                               name="date_from"
                               class="form-control">

                    </div>

                    {{-- Date To --}}

                    <div class="col-md-2">

                        <label>@lang('messages.date-to')</label>

                        <input type="date"
                               name="date_to"
                               class="form-control">

                    </div>

                    <div class="col-md-2">

                        <label>&nbsp;</label>

                        <button type="submit"
                                class="btn btn-primary btn-block">

                            @lang('messages.generate-report')

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
