@props(['label' => null, 'name', 'type' => 'text'])

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        {{ $attributes->class([
            'block w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500',
            'border-rose-400' => $errors->has($name),
            'border' => true,
        ]) }}
    />
    @error($name)
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
