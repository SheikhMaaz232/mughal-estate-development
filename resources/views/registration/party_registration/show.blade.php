@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title text-primary">
                <i class="fa fa-building me-1"></i>@lang('messages.party_details')
            </h3>
            <div class="block-options">
                <a href="{{ route('parties.edit', $party->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit me-1"></i>@lang('messages.edit-parties')
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <!-- party Information Card -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-info-circle text-primary me-1"></i>@lang('messages.basic-information')
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped">
                                <tbody>
                                    <tr>
                                        <th width="30%">@lang('messages.name')</th>
                                        <td>
                                            {{ App::getLocale() === 'ur' ? $party->name_ur ?? '-' : $party->name_en ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.father-name')</th>
                                        <td>
                                            {{ App::getLocale() === 'ur' ? $party->father_name_ur ?? '-' : $party->father_name_en ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.cnic_no')</th>
                                        <td>{{ $party->cnic_no ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.cast')</th>
                                        <td>
                                            {{ App::getLocale() === 'ur' ? $party->cast->title_ur ?? '-' : $party->cast->title_en ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.occupation')</th>
                                        <td>
                                            {{ App::getLocale() === 'ur' ? $party->occupation->title_ur ?? '-' : $party->occupation->title_en ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.residential-Status')</th>
                                        <td>
                                            {{ App::getLocale() === 'ur' ? $party->residentialStatus->title_ur ?? '-' : $party->residentialStatus->title_en ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.contact_no')(1)</th>
                                        <td>{{ $party->contact_number_1 ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.contact_no')(2)</th>
                                        <td>{{ $party->contact_number_2 ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.whatsapp_no')</th>
                                        <td>{{ $party->whatsApp_no ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Logo Display Card -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-image text-primary me-1"></i>@lang('messages.profile') @lang('messages.image')
                            </h4>
                        </div>
                        <div class="card-body" style="height: 490px !important;">
                            @if ($party->profile_image)
                                <div
                                    style="width:100%; height:450px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                    <img src="{{ asset('storage/' . $party->profile_image) }}" alt="@lang('messages.no_image')"
                                        style="max-width:100%; max-height:100%; object-fit:contain;" class="rounded border">
                                </div>
                            @else
                                <div class="py-4">
                                    <i class="fa fa-building fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">@lang('messages.no_image')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.cnic_images')
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- party Cnin Images -->
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h4 class="card-title mb-0">
                                            <i class="fa fa-image text-primary me-1"></i>@lang('messages.cnic_front')
                                            @lang('messages.image')
                                        </h4>
                                    </div>
                                    <div class="card-body" style="height: 290px !important;">
                                        @if ($party->cnic_front_image)
                                            <div
                                                style="width:100%; height:250px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                                <img src="{{ asset('storage/' . $party->cnic_front_image) }}"
                                                    alt="@lang('messages.no_image')"
                                                    style="max-width:100%; max-height:100%; object-fit:contain;"
                                                    class="rounded border">
                                            </div>
                                        @else
                                            <div class="py-4">
                                                <i class="fa fa-building fa-4x text-muted mb-3"></i>
                                                <p class="text-muted">@lang('messages.no_image')</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Logo Display Card -->
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h4 class="card-title mb-0">
                                            <i class="fa fa-image text-primary me-1"></i>@lang('messages.cnic_back')
                                            @lang('messages.image')
                                        </h4>
                                    </div>
                                    <div class="card-body" style="height: 290px !important;">
                                        @if ($party->cnic_back_image)
                                            <div
                                                style="width:100%; height:250px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                                <img src="{{ asset('storage/' . $party->cnic_back_image) }}"
                                                    alt="@lang('messages.no_image')"
                                                    style="max-width:100%; max-height:100%; object-fit:contain;"
                                                    class="rounded border">
                                            </div>
                                        @else
                                            <div class="py-4">
                                                <i class="fa fa-building fa-4x text-muted mb-3"></i>
                                                <p class="text-muted">@lang('messages.no_image')</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Address Card -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.additional_information')
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.ntn_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $party->ntn_no ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.gst_no')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $party->gst_no ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.business_name')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $party->business_name_ur ?? __('messages.no_available') : $party->business_name_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.business_address')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $party->business_address_ur ?? __('messages.no_available') : $party->business_address_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.home_address')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ App::getLocale() === 'ur' ? $party->home_address_ur ?? __('messages.no_available') : $party->home_address_en ?? __('messages.no_available') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3">@lang('messages.remarks')</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $party->remarks ?? __('messages.no_available') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.bank_details')
                        </h4>
                    </div>
                    <div class="card-body">
                        @forelse ($partyBanks as $partyBank)
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.banks')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ App::getLocale() === 'ur' ? $partyBank->bank->name_ur ?? __('messages.no_available') : $partyBank->bank->name_en ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.account_title')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $partyBank->account_title ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.account_number')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $partyBank->account_number ?? __('messages.no_available') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">@lang('messages.branch_code')</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $partyBank->branch_code ?? __('messages.no_available') }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mt-3 mb-3">
                                <hr class="my-4" style="width:50%; height:8px; background-color:#000000; border:none; border-radius:4px;">
                            </div>

                        @empty
                            <p class="text-muted">@lang('messages.no_available')</p>
                        @endforelse
                    </div>

                </div>
            </div>

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('parties.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>
@endsection
