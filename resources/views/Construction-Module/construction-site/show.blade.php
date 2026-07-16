@extends('layouts.backend')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.construction-site-details')</h3>
    </div>
    <div class="block-content block-content-full">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light w-25">@lang('messages.id')</th>
                            <td>{{ $site->id }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.company')</th>
                            <td>{{ App::getLocale() === 'ur' ? $site->company->name_ur : $site->company->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.project')</th>
                            <td>{{ App::getLocale() === 'ur' ? $site->project->name_ur : $site->project->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.party')</th>
                            <td>{{ $site->party ? (App::getLocale() === 'ur' ? $site->party->name_ur : $site->party->name_en) : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.name') (EN)</th>
                            <td>{{ $site->name_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.name') (اردو)</th>
                            <td dir="rtl">{{ $site->name_ur }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.description') (EN)</th>
                            <td>{{ $site->description_en ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.description') (اردو)</th>
                            <td dir="rtl">{{ $site->description_ur ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.address') (EN)</th>
                            <td>{{ $site->address_en }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.address') (اردو)</th>
                            <td dir="rtl">{{ $site->address_ur }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.estimated-start-date')</th>
                            <td>{{ $site->estimated_start_date ? $site->estimated_start_date->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.estimated-end-date')</th>
                            <td>{{ $site->estimated_end_date ? $site->estimated_end_date->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.status')</th>
                            <td>
                                @if ($site->status === 'pending')
                                    <span class="badge bg-warning">@lang('messages.pending')</span>
                                @elseif ($site->status === 'ongoing')
                                    <span class="badge bg-info">@lang('messages.ongoing')</span>
                                @elseif ($site->status === 'completed')
                                    <span class="badge bg-success">@lang('messages.completed')</span>
                                @elseif ($site->status === 'on-hold')
                                    <span class="badge bg-danger">@lang('messages.on-hold')</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.created-at')</th>
                            <td>{{ $site->created_at ? $site->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">@lang('messages.updated-at')</th>
                            <td>{{ $site->updated_at ? $site->updated_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('construction-sites.edit', $site->id) }}" class="btn btn-alt-primary">@lang('messages.edit')</a>
            <a href="{{ route('tenders.index', ['constructionSiteId' => $site->id]) }}" class="btn btn-alt-success">@lang('messages.manage-tenders')</a>
            <a href="{{ route('tenders.create.site', $site->id) }}" class="btn btn-alt-info">@lang('messages.add-new-tender')</a>
            <form action="{{ route('construction-sites.destroy', $site->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-alt-danger" onclick="return confirm('@lang('messages.confirm-delete')')">@lang('messages.delete')</button>
            </form>
            <a href="{{ route('construction-sites.index') }}" class="btn btn-alt-secondary">@lang('messages.back')</a>
        </div>
    </div>
</div>
@endsection
