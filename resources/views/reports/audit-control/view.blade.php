@extends('layouts.backend')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">@lang('messages.audit_control_report')</h2>
            <i class="fa fa-shield-halved text-primary"></i>
        </div>
        <div class="card-body">
            <p class="text-muted">@lang('messages.audit_control_hint')</p>
            <form action="{{ route('reports.audit.control.report') }}" method="GET" target="_blank">
                <div class="row">
                    <div class="col-md-3 mb-3"><label class="form-label" for="from_date">@lang('messages.from_date') <span class="text-danger">*</span></label><input type="date" class="form-control" id="from_date" name="from_date" value="{{ old('from_date', request('from_date', now()->startOfMonth()->format('Y-m-d'))) }}" required></div>
                    <div class="col-md-3 mb-3"><label class="form-label" for="to_date">@lang('messages.to_date') <span class="text-danger">*</span></label><input type="date" class="form-control" id="to_date" name="to_date" value="{{ old('to_date', request('to_date', now()->format('Y-m-d'))) }}" required></div>
                    <div class="col-md-2 mb-3"><label class="form-label" for="user_id">@lang('messages.audit_user')</label><select class="form-select" id="user_id" name="user_id"><option value="">@lang('messages.all_users')</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ App::getLocale() === 'ur' ? ($user->name_ur ?: $user->name_en) : $user->name_en }}</option>@endforeach</select></div>
                    <div class="col-md-2 mb-3"><label class="form-label" for="model">@lang('messages.audit_model')</label><select class="form-select" id="model" name="model"><option value="">@lang('messages.all_models')</option>@foreach($models as $model)<option value="{{ $model }}" @selected(request('model') === $model)>{{ class_basename($model) }}</option>@endforeach</select></div>
                    <div class="col-md-2 mb-3"><label class="form-label" for="event">@lang('messages.audit_event')</label><select class="form-select" id="event" name="event"><option value="">@lang('messages.all_events')</option>@foreach($events as $event)<option value="{{ $event }}" @selected(request('event') === $event)>{{ ucfirst($event) }}</option>@endforeach</select></div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-file-shield me-1"></i> @lang('messages.generate_report')</button>
                <a href="{{ route('reports.audit.control.view') }}" class="btn btn-secondary">@lang('messages.reset')</a>
            </form>
        </div>
    </div>
</div>
@endsection
