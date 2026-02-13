@props(['label', 'name' => 'remember'])

<div class="flex items-center">
    <input 
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        {{ old($name) ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900 transition duration-150 ease-in-out']) }}
    />
    <label for="{{ $name }}" class="ml-2 block text-sm text-gray-900 cursor-pointer">
        {{ $label }}
    </label>
</div>
