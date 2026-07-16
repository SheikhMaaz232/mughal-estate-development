@extends('layouts.backend')

@section('content')
    <div class="container">
        <h2>@lang('messages.land_registration')</h2>

        <div class="card p-3 mb-4">
            <h4>@lang('messages.project'):
                {{ App::getLocale() === 'ur' ? $land->project->name_ur ?? '-' : $land->project->name_en ?? '-' }}</h4>
            <p><strong>@lang('messages.seller_account'):</strong>
                {{ App::getLocale() === 'ur' ? $land->sellerAccount->name_ur ?? '-' : $land->sellerAccount->name_en ?? '-' }}
            </p>
            <p><strong>@lang('messages.buyer_account'):</strong>
                {{ App::getLocale() === 'ur' ? $land->buyerAccount->name_ur ?? '-' : $land->buyerAccount->name_en ?? '-' }}
            </p>
            <p><strong>@lang('messages.commission_account'):</strong>
                {{ App::getLocale() === 'ur' ? $land->commissionAccount->name_ur ?? '-' : $land->commissionAccount->name_en ?? '-' }}
            </p>
            <div class="row">
                <div class="col-md-3">
                    <p><strong>@lang('messages.total_acre'):</strong> {{ $land->total_acre }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>@lang('messages.total_kanal'):</strong> {{ $land->total_kanal }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>@lang('messages.total_square_feet'):</strong> {{ $land->total_square_feet }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>@lang('messages.land_amount'):</strong> {{ $land->land_amount }}</p>
                </div>
            </div>
            <div class="row">
            <div class="col-md-3">
                <p><strong>@lang('messages.commission_amount'):</strong> {{ $land->commission_amount }}</p>
            </div>
            <div class="col-md-9">
                <p><strong>@lang('messages.remarks'):</strong> {{ $land->remarks }}</p>
            </div>
            </div>
        </div>

        <h4>@lang('messages.details_entries')</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>@lang('messages.khawat_no')</th>
                    <th>@lang('messages.fard_id_no')</th>
                    <th>@lang('messages.registry_no')</th>
                    <th>@lang('messages.moza')</th>
                    <th>@lang('messages.acre')</th>
                    <th>@lang('messages.kanal')</th>
                    <th>@lang('messages.square_feet')</th>
                    <th>@lang('messages.remarks')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($land->details as $detail)
                    <tr>
                        <td>{{ $detail->khawat_no }}</td>
                        <td>{{ $detail->fard_id_no }}</td>
                        <td>{{ $detail->registry_no }}</td>
                        <td>{{ $detail->moza }}</td>
                        <td>{{ $detail->acre }}</td>
                        <td>{{ $detail->kanal }}</td>
                        <td>{{ $detail->square_feet }}</td>
                        <td>{{ $detail->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('lands.index') }}" class="btn btn-secondary">@lang('messages.back')</a>
    </div>
@endsection
