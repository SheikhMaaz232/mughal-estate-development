@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>@lang('messages.land_registration')</h2>
        <div>
            <a href="{{ route('lands.create') }}" class="btn btn-primary">
                @lang('messages.add_land')
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($lands->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('messages.project')</th>
                        <th scope="col">@lang('messages.seller_account')</th>
                        <th scope="col">@lang('messages.buyer_account')</th>
                        <th scope="col">@lang('messages.total_acre')</th>
                        <th scope="col">@lang('messages.total_kanal')</th>
                        <th scope="col">@lang('messages.land_amount')</th>
                        <th scope="col">@lang('messages.details_entries')</th>
                        <th scope="col">@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lands as $land)
                        <tr>
                            <td>{{ $land->id }}</td>
                            <td>{{ $land->project ?? '-' }}</td>
                            <td>{{ $land->seller_account ?? '-' }}</td>
                            <td>{{ $land->buyer_account ?? '-' }}</td>
                            <td>{{ $land->total_acre ?? '-' }}</td>
                            <td>{{ $land->total_kanal ?? '-' }}</td>
                            <td>{{ $land->land_amount ?? '-' }}</td>
                            <td>{{ $land->details->count() ?? 0 }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('lands.show', $land->id) }}" class="btn btn-sm btn-info">
                                    @lang('messages.view', [], null)
                                </a>

                                <a href="{{ route('lands.edit', $land->id) }}" class="btn btn-sm btn-warning">
                                    @lang('messages.edit_land')
                                </a>

                                <form action="{{ route('lands.destroy', $land->id) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('messages.confirm_delete')');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">@lang('messages.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $lands->links() }}
        </div>
    @else
        <div class="alert alert-info">
            @lang('messages.no_records_found')
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-light">@lang('messages.back')</a>
    </div>
</div>
@endsection
