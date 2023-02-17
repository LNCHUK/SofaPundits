@if ($preference->type == 'checkbox')
    <input
        type="checkbox"
        name="{{ $preference->slug }}"
        class="rounded-md shadow-sm w-6 h-6 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        value="1"
        @if ($preference->user_selected_value ?? $preference->default_value) checked @endif
    />
@elseif ($preference->type == 'select')
    <x-select name="{{ $preference->slug }}" id="{{ $preference->slug }}">
        @foreach ($preference->choices as $value => $label)
            <option
                value="{{ $value }}"
                @if (($preference->user_selected_value ?? $preference->default_value) == $value) selected @endif
            >
                {{ $label }}
            </option>
        @endforeach
    </x-select>
@else
    -
@endif