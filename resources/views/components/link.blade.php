@props(['href' => '#', 'icon' => null, 'external' => false])

<a 
    href="{{ $href }}" 
    {{ $external ? 'target="_blank" rel="noopener noreferrer"' : '' }}
    {{ $attributes->merge(['class' => 'inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 font-medium transition']) }}
>
    @if($icon)
        {!! $icon !!}
    @endif
    <span>{{ $slot }}</span>
    @if($external)
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
    @endif
</a>
