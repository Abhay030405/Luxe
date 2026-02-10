@props(['label', 'name', 'type' => 'text', 'required' => false, 'value' => ''])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-900">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm transition']) }}
    />
    
    @error($name)
        <p class="text-sm text-red-600 flex items-center space-x-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
