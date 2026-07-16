@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.devices-management')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.list-of-devices')</h2>
            </div>
            <a href="{{ route('payroll.devices.create') }}" class="btn btn-sm btn-primary">@lang('payroll::messages.add-new')</a>
        </div>
    </div>
</div>
<div class="content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('messages.name')(EN)</th>
                        <th>@lang('messages.name')(UR)</th>
                        <th>@lang('payroll::messages.IPAddress')</th>
                        <th style="width: 150px;">@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($devices as $device)
                    <td>{{ $device->id }}</td>
                    <td>{{ $device->name_en }}</td>
                    <td>{{ $device->name_ur }}</td>
                    <td>{{ $device->ip_address }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <!-- Edit Button -->
                            <a href="{{ route('payroll.devices.edit', $device->id) }}"
                               class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                               data-bs-toggle="tooltip"
                               aria-label="Edit Device"
                               data-bs-original-title="Edit Device">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('payroll.devices.destroy', $device->id) }}" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                 {{ $devices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
