@extends('layouts.backend')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.land-transfer-details')</h3>
        <div class="block-options">
            <a href="{{ route('land-transfers.index') }}" class="btn btn-sm btn-alt-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> @lang('messages.back-to-list')
            </a>
            <a href="{{ route('land-transfers.edit', $landTransfer->id) }}" class="btn btn-sm btn-primary me-2">
                <i class="fa fa-edit me-1"></i> @lang('messages.edit-land-transfer')
            </a>
            <form action="{{ route('land-transfers.destroy', $landTransfer->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('@lang('messages.confirm-delete')')">
                    <i class="fa fa-trash me-1"></i> @lang('messages.delete')
                </button>
            </form>
        </div>
    </div>
    <div class="block-content">
        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">@lang('messages.basic-information')</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">@lang('messages.transfer-date'):</th>
                                <td>{{ $landTransfer->transfer_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.registry-type'):</th>
                                <td>{{ App::getLocale() === 'ur' ? $landTransfer->registryType->title_ur ?? '-' : $landTransfer->registryType->title_en ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.purchaser'):</th>
                                <td>{{ App::getLocale() === 'ur' ? $landTransfer->purchaserAccount->name_ur ?? '-' : $landTransfer->purchaserAccount->name_en ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.seller'):</th>
                                {{-- <td>{{ $landTransfer->sellerAccount->name_en ?? 'N/A' }}</td> --}}
                                <td>{{ App::getLocale() === 'ur' ? $landTransfer->sellerAccount->name_ur ?? '-' : $landTransfer->sellerAccount->name_en ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.value'):</th>
                                <td>{{ number_format($landTransfer->value, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Land Details -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">@lang('messages.land-details')</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">@lang('messages.fard-no'):</th>
                                <td>{{ $landTransfer->fard_no }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.khawat-no'):</th>
                                <td>{{ $landTransfer->khawat_no }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.khatoni-no'):</th>
                                <td>{{ $landTransfer->khatoni_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.mushtarqa-khata'):</th>
                                <td>{{ $landTransfer->mushtarqa_khata ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.makhsoos-raqba'):</th>
                                <td>{{ $landTransfer->makhsoos_raqba ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">@lang('messages.additional-details')</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">@lang('messages.qitaat'):</th>
                                <td>{{ $landTransfer->qitaat ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.saalam-khata'):</th>
                                <td>{{ $landTransfer->saalam_khata ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.hissa-mutaliqa'):</th>
                                <td>{{ $landTransfer->hissa_mutaliqa ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>@lang('messages.raqba-muntaqila'):</th>
                                <td>{{ $landTransfer->raqba_muntaqila ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Attachments -->
            @if($landTransfer->attachment_1 || $landTransfer->attachment_2 || $landTransfer->attachment_3)
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">@lang('messages.attachments')</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($landTransfer->attachment_1)
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <img src="{{ Storage::url('land-transfers/' . $landTransfer->attachment_1) }}"
                                         alt="Attachment 1" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    <small class="text-muted d-block mt-2">{{  $landTransfer->attachment_1 }}</small>
                                    <a href="{{ Storage::url('land-transfers/' . $landTransfer->attachment_1) }}"
                                       target="_blank" class="btn btn-sm btn-alt-primary mt-1">
                                        <i class="fa fa-expand me-1"></i> @lang('messages.view-full')
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($landTransfer->attachment_2)
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <img src="{{ Storage::url('land-transfers/' . $landTransfer->attachment_2) }}"
                                         alt="Attachment 2" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    <small class="text-muted d-block mt-2">{{ $landTransfer->attachment_2 }}</small>
                                    <a href="{{ Storage::url('land-transfers/' . $landTransfer->attachment_2) }}"
                                       target="_blank" class="btn btn-sm btn-alt-primary mt-1">
                                        <i class="fa fa-expand me-1"></i> @lang('messages.view-full')
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($landTransfer->attachment_3)
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <img src="{{ Storage::url('land-transfers/' . $landTransfer->attachment_3) }}"
                                         alt="Attachment 3" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    <small class="text-muted d-block mt-2">{{ $landTransfer->attachment_3 }}</small>
                                    <a href="{{ Storage::url('land-transfers/' . $landTransfer->attachment_3) }}"
                                       target="_blank" class="btn btn-sm btn-alt-primary mt-1">
                                        <i class="fa fa-expand me-1"></i> @lang('messages.view-full')
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Old Images Section (if you still need it) -->
            @if($landTransfer->image1 || $landTransfer->image2 || $landTransfer->image3)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">@lang('messages.images')</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($landTransfer->attachment_1)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/land-transfers/' . $landTransfer->attachment_1) }}"
                                     alt="Image 1" class="img-fluid rounded">
                                <small class="text-muted d-block text-center mt-1">Attachment 1</small>
                            </div>
                            @endif
                            @if($landTransfer->attachment_2)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/land-transfers/' . $landTransfer->attachment_2) }}"
                                     alt="Image 2" class="img-fluid rounded">
                                <small class="text-muted d-block text-center mt-1">Attachment 2</small>
                            </div>
                            @endif
                            @if($landTransfer->attachment_3)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/land-transfers/' . $landTransfer->attachment_3) }}"
                                     alt="Image 3" class="img-fluid rounded">
                                <small class="text-muted d-block text-center mt-1">Attachment 3</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
