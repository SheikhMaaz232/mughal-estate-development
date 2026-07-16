@extends('layouts.backend')

@section('content')
@if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.create-land-transfer')</h3>
        <div class="block-options">
            <a href="{{ route('land-transfers.index') }}" class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-arrow-left me-1"></i> @lang('messages.back-to-list')
            </a>
        </div>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('land-transfers.store') }}" method="POST" enctype="multipart/form-data">
            @include('lands.land-transfers.partials.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2();
});
</script>
@endpush
