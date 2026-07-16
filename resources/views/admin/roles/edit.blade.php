@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">@lang('messages.edit-role')</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label">@lang('messages.name')</label>
                        <input type="text" name="name" class="form-control" value="{{ $role->name }}" required readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">@lang('messages.name') (EN)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ $role->name_en }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">@lang('messages.name') (UR)</label>
                        <input type="text" name="name_ur" class="form-control keyboardInput" value="{{ $role->name_ur }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">@lang('messages.permissions')</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-vcenter">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.name-of-permission')</th>
                                        <th width="150px" class="text-center">@lang('messages.assign')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>

                                            <td>{{ $permission->name }}</td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>
                </form>
            </div>
        </div>
    </div>

    <style>
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .table-responsive {
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .form-check-input {
            margin-top: 0;
        }
    </style>
@endsection
