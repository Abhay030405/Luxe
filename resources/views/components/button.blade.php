@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'href' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 shadow-sm hover:shadow focus:ring-slate-900',
        'secondary' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-200',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-600',
        'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-slate-900',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

@if($href)
<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size]]) }}
>
    {{ $slot }}
</a>
@else
<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size]]) }}
>
    {{ $slot }}
</button>
@endif
