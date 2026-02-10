@props(['title' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white shadow rounded-lg border border-gray-200']) }}>
    @if($title)
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    <div class="{{ $padding ? 'px-6 py-5' : '' }}">
        {{ $slot }}
    </div>
</div>
