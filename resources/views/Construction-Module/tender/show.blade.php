@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('messages.tender-details')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">{{ App::getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="content">
<div class="block block-rounded">
    <div class="block-content block-content-full">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light w-25">@lang('messages.id')</th>
                            <td>{{ $tender->id }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.construction-site')</th>
                            <td>{{ App::getLocale() === 'ur' ? $tender->constructionSite->name_ur : $tender->constructionSite->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.title') (EN)</th>
                            <td>{{ $tender->title_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.title') (اردو)</th>
                            <td dir="rtl">{{ $tender->title_ur }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.description') (EN)</th>
                            <td>{{ $tender->description_en ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.description') (اردو)</th>
                            <td dir="rtl">{{ $tender->description_ur ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.work-type')</th>
                            <td>{{ $tender->work_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.contractee-account')</th>
                            <td>{{ App::getLocale() === 'ur' ? $tender->contracteeAccount->name_ur : $tender->contracteeAccount->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.contractor-account')</th>
                            <td>{{ App::getLocale() === 'ur' ? $tender->contractorAccount->name_ur : $tender->contractorAccount->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.revenue-account')</th>
                            <td>{{ App::getLocale() === 'ur' ? $tender->revenueAccount->name_ur : $tender->revenueAccount->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.expense-account')</th>
                            <td>{{ App::getLocale() === 'ur' ? $tender->expenseAccount->name_ur : $tender->expenseAccount->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.estimated-cost')</th>
                            <td>{{ $tender->estimated_cost ? number_format($tender->estimated_cost, 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.start-date')</th>
                            <td>{{ $tender->start_date ? $tender->start_date->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.end-date')</th>
                            <td>{{ $tender->end_date ? $tender->end_date->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.status')</th>
                            <td>
                                @if ($tender->status === 'draft')
                                    <span class="badge bg-secondary">@lang('messages.draft')</span>
                                @elseif ($tender->status === 'approved')
                                    <span class="badge bg-info">@lang('messages.approved')</span>
                                @elseif ($tender->status === 'in_progress')
                                    <span class="badge bg-warning">@lang('messages.in-progress')</span>
                                @elseif ($tender->status === 'completed')
                                    <span class="badge bg-success">@lang('messages.completed')</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.created-at')</th>
                            <td>{{ $tender->created_at ? $tender->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.updated-at')</th>
                            <td>{{ $tender->updated_at ? $tender->updated_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('tenders.edit', $tender->id) }}" class="btn btn-alt-primary">@lang('messages.edit')</a>
            <form action="{{ route('tenders.destroy', $tender->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-alt-danger" onclick="return confirm('@lang('messages.confirm-delete')')">@lang('messages.delete')</button>
            </form>
            <a href="{{ route('tenders.index', ['constructionSiteId' => $tender->construction_site_id]) }}" class="btn btn-alt-secondary">@lang('messages.back')</a>
        </div>
    </div>
</div>
</div>
@endsection
