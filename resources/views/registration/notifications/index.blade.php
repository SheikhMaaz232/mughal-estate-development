@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.notifications')</h3>
            @if(auth()->user()->unreadNotifications()->exists())
                <div class="block-options">
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-alt-primary">
                            @lang('messages.mark_all_read')
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <div class="block-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse($notifications as $notification)
                @php
                    $notificationData = $notification->data;
                    $localeSuffix = app()->getLocale() === 'ur' ? 'ur' : 'en';
                @endphp
                <div class="border rounded p-3 mb-3 {{ $notification->read_at ? '' : 'bg-body-light border-primary' }}">
                    <form method="POST" action="{{ route('notifications.open', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-start text-decoration-none w-100">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold text-dark">
                                        {{ $notificationData['title_' . $localeSuffix] ?? __('messages.notifications') }}
                                    </div>
                                    <div class="text-muted mt-1">
                                        {{ $notificationData['message_' . $localeSuffix] ?? '' }}
                                    </div>
                                </div>
                                @if(!$notification->read_at)
                                    <span class="badge bg-primary">@lang('messages.unread')</span>
                                @endif
                            </div>
                            <small class="text-muted d-block mt-2">{{ $notification->created_at->diffForHumans() }}</small>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center text-muted py-4">@lang('messages.no_notifications')</div>
            @endforelse

            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
