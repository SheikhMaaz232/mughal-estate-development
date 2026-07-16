@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.boq-details')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        {{ App::getLocale() === 'ur' ? $boqMaster->title_ur : $boqMaster->title_en }}
                    </h2>
                </div>
                <a href="{{ route('boq-masters.index') }}" class="btn btn-sm btn-secondary">@lang('messages.back')</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.general-information')</h3>
                <div class="block-options">
                    <a href="{{ route('boq-masters.edit', $boqMaster->id) }}" class="btn btn-sm btn-primary">@lang('messages.edit')</a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.title') @lang('messages.english')</label>
                        <p>{{ $boqMaster->title_en }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.title') @lang('messages.urdu')</label>
                        <p>{{ $boqMaster->title_ur }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.construction-site')</label>
                        <p>{{ App::getLocale() === 'ur' ? $boqMaster->constructionSite->name_ur : $boqMaster->constructionSite->name_en }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.tender')</label>
                        <p>{{ App::getLocale() === 'ur' ? $boqMaster->tender->title_ur : $boqMaster->tender->title_en }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.total_amount')</label>
                        <p>{{ number_format($boqMaster->total_amount, 2) }}</p>
                    </div>
                </div>


            </div>
        </div>

        <!-- BOQ Details -->
        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.boq-details')</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('messages.id')</th>
                                <th>@lang('messages.item')</th>
                                <th>@lang('messages.quantity')</th>
                                <th>@lang('messages.rate')</th>
                                <th>@lang('messages.gross-amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($boqMaster->details as $detail)
                                <tr>
                                    <td>{{ $detail->id }}</td>
                                    <td>
                                        @if ($detail->item)
                                            {{ App::getLocale() === 'ur' ? $detail->item->name_ur : $detail->item->name_en }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($detail->quantity, 4) }}</td>
                                    <td class="text-right">{{ number_format($detail->rate, 2) }}</td>
                                    <td class="text-right">{{ number_format($detail->gross_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        @lang('messages.no-records-found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="4" class="text-end">@lang('messages.total'):</td>
                                <td class="text-right">{{ number_format($boqMaster->details->sum('gross_amount'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
