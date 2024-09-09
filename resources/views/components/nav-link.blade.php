@props(['active'])

@php
    $classes = $active ?? false ? 'text-gray-900 bg-gray-100' : 'text-gray-500 hover:text-gray-900';
@endphp

<a
    {{ $attributes->merge(['class' => 'flex items-center gap-3 rounded-lg px-3 py-2 font-semibold hover:bg-gray-200 transition-all ' . $classes]) }}>
    {{ $slot }}
</a>
