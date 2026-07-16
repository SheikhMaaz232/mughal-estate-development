@extends('layouts.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-4">@lang('messages.land-registration-details')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.view-land-registration-details')</h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('land-registrations.index') }}">@lang('messages.land-registrations')</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        @lang('messages.details')
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.land-registration-information')</h3>
            <div class="block-options">
                <a href="{{ route('land-registrations.edit', $landRegistration->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-fw fa-pencil-alt"></i> @lang('messages.edit')
                </a>
                <a href="{{ route('land-registrations.index') }}" class="btn btn-sm btn-alt-secondary">
                    <i class="fa fa-fw fa-arrow-left"></i> @lang('messages.back-to-list')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-striped">
                        <tr>
                            <th width="40%">@lang('messages.id'):</th>
                            <td>{{ $landRegistration->id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.project'):</th>
                            <td>{{ $landRegistration->project->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.party-account'):</th>
                            <td>{{ $landRegistration->partyAccount->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.khawat-number'):</th>
                            <td>{{ $landRegistration->khawat_number ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-striped">
                        <tr>
                            <th width="40%">@lang('messages.kanal'):</th>
                            <td>{{ number_format($landRegistration->kanal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.merla'):</th>
                            <td>{{ number_format($landRegistration->merla, 2) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.square-feet'):</th>
                            <td>{{ number_format($landRegistration->square_feet, 2) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('messages.total-merla'):</th>
                            <td><strong class="text-primary">{{ number_format($landRegistration->total_merla, 4) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($landRegistration->remarks)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label"><strong>@lang('messages.remarks'):</strong></label>
                        <div class="alert alert-info mb-0">
                            {{ $landRegistration->remarks }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Calculation Details -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <h5 class="alert-heading">@lang('messages.calculation-breakdown')</h5>
                        @php
                            $projectSquareFeetPerMerla = $landRegistration->project->square_feet_per_merla ?? 272.25;
                            $kanalToMerla = $landRegistration->kanal * 20;
                            $squareFeetToMerla = $landRegistration->square_feet / $projectSquareFeetPerMerla;
                        @endphp
                        <div class="row">
                            <div class="col-md-4">
                                <strong>@lang('messages.kanal-to-merla'):</strong><br>
                                {{ number_format($landRegistration->kanal, 2) }} × 20 = <strong>{{ number_format($kanalToMerla, 4) }}</strong>
                            </div>
                            <div class="col-md-4">
                                <strong>@lang('messages.merla'):</strong><br>
                                {{ number_format($landRegistration->merla, 2) }}
                            </div>
                            <div class="col-md-4">
                                <strong>@lang('messages.square-feet-to-merla'):</strong><br>
                                {{ number_format($landRegistration->square_feet, 2) }} ÷ {{ number_format($projectSquareFeetPerMerla, 2) }} = <strong>{{ number_format($squareFeetToMerla, 4) }}</strong>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <strong>@lang('messages.total-merla'):</strong>
                            {{ number_format($kanalToMerla, 4) }} + {{ number_format($landRegistration->merla, 2) }} + {{ number_format($squareFeetToMerla, 4) }} =
                            <strong class="text-success">{{ number_format($landRegistration->total_merla, 4) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Information -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>@lang('messages.created-by'):</strong> {{ $landRegistration->createdBy->name ?? 'N/A' }}<br>
                                    <strong>@lang('messages.created-at'):</strong> {{ $landRegistration->created_at->format('M d, Y h:i A') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>@lang('messages.updated-by'):</strong> {{ $landRegistration->updatedBy->name ?? 'N/A' }}<br>
                                    <strong>@lang('messages.updated-at'):</strong> {{ $landRegistration->updated_at->format('M d, Y h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
