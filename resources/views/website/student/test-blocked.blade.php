@extends('website.layout')

@section('title', 'Test Unavailable')

@section('content')
<div style="min-height: 60vh;"></div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: @json($icon ?? 'info'),
            title: @json($title ?? 'Test Unavailable'),
            text: @json($message ?? ''),
            confirmButtonText: 'OK',
            confirmButtonColor: '#f7931e',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(function () {
            window.location.href = @json($redirectUrl ?? route('student.dashboard'));
        });
    });
</script>
@endpush
