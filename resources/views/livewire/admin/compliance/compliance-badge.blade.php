@php
    $colors = [
        'valid' => 'bg-green-100 text-green-700',
        'expiring' => 'bg-yellow-100 text-yellow-700',
        'expired' => 'bg-orange-100 text-orange-700',
        'non_compliant' => 'bg-red-100 text-red-700',
    ];
@endphp

<span class="px-2 py-1 text-xs rounded {{ $colors[$status] ?? 'bg-gray-100' }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>