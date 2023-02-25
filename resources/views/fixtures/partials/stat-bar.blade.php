<div class="text-center mb-4">
    <div>
        <p class="mb-1 text-xs uppercase opacity-80">
            {{ $title }}
        </p>
    </div>
    <div class="flex items-center gap-x-6">
        <div class="w-6 font-bold text-sm">
            {{ str_replace('.00', '', $homeStats->{$statField}) }}@if ($isPercentage ?? false)%@endif
        </div>
        <div class="flex-1">
            <div class="flex h-3 rounded-lg overflow-hidden border border-gray-300">
                <div
                    style="
                        background: #{{ $homeColour }};
                        @if ($homeStats->{$statField} + $awayStats->{$statField} == 0)
                            width: 50%
                        @else
                            width: {{ ($homeStats->{$statField} / ($homeStats->{$statField} + $awayStats->{$statField})) * 100 }}%;
                        @endif
                    "
                ></div>
                <div
                    style="
                        background: #{{ $awayColour }};
                        @if ($homeStats->{$statField} + $awayStats->{$statField} == 0)
                            width: 50%
                        @else
                            width: {{ ($awayStats->{$statField} / ($homeStats->{$statField} + $awayStats->{$statField})) * 100 }}%;
                        @endif
                    "
                ></div>
            </div>
        </div>
        <div class="w-6 font-bold text-sm">
            {{ str_replace('.00', '', $awayStats->{$statField}) }}@if ($isPercentage ?? false)%@endif
        </div>
    </div>
</div>