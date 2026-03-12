@php
    $success = session('success');
    $error = session('error');
@endphp

@if ($success || $error)
    <div class="toast-stack" data-toast>
        <div class="toast {{ $error ? 'toast-error' : 'toast-success' }}">
            <span>{{ $error ?? $success }}</span>
            <button type="button" class="toast-close" data-toast-close aria-label="Close">&times;</button>
        </div>
    </div>
@endif
