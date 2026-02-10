@props(['title', 'description' => null, 'icon' => null, 'action' => null])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    @if($icon)
        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
            {!! $icon !!}
        </div>
    @endif
    
    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    
    @if($description)
        <p class="text-sm text-gray-600 mb-6">{{ $description }}</p>
    @endif
    
    @if($action)
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>
