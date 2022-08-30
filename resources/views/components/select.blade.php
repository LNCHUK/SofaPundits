@props([
    'disabled' => false
])
<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->except('options')->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
    @if ($slot && $slot != "")
        {{ $slot }}
    @else
        @foreach ($attributes['options'] as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    @endif
</select>
