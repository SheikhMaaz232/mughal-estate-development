@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.user_password_reset')</h3>
        </div>
        <div class="block-content">
            <p class="text-muted">@lang('messages.user_password_reset_hint')</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="alert alert-info">
                <strong>{{ $user->name_en }}</strong>
                @if($user->name_ur)
                    <span>({{ $user->name_ur }})</span>
                @endif
                <div>{{ $user->email }} — @lang('messages.id'): {{ $user->id }}</div>
            </div>

            <form method="POST" action="{{ route('user-passwords.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="password" class="form-label">@lang('messages.new_password')</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="password"
                                aria-label="@lang('messages.show_password')"
                                title="@lang('messages.show_password')">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">@lang('messages.confirm_new_password')</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" autocomplete="new-password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="password_confirmation"
                                aria-label="@lang('messages.show_password')"
                                title="@lang('messages.show_password')">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">@lang('messages.reset_password')</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (!input) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                this.setAttribute('aria-label', isHidden
                    ? @json(__('messages.hide_password'))
                    : @json(__('messages.show_password')));
                this.setAttribute('title', this.getAttribute('aria-label'));
                this.querySelector('i').classList.toggle('fa-eye', !isHidden);
                this.querySelector('i').classList.toggle('fa-eye-slash', isHidden);
            });
        });
    });
</script>
@endsection
