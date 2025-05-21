@props(['label', 'name', 'value' => '', 'step' => '1', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-medium mb-2">{{ $label }}</label>
    <input
        type="number"
        name="{{ $name }}"
        id="{{ $name }}"
        step="{{ $step }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full border rounded px-3 py-2']) }}
    />
</div>
