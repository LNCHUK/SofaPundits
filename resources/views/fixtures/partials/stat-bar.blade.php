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
                        width: {{ ($homeStats->{$statField} / ($homeStats->{$statField} + $awayStats->{$statField})) * 100 }}%;
                    "
                ></div>
                <div
                    style="
                        background: #{{ $awayColour }};
                        width: {{ ($awayStats->{$statField} / ($homeStats->{$statField} + $awayStats->{$statField})) * 100 }}%;
                    "
                ></div>
            </div>
        </div>
        <div class="w-6 font-bold text-sm">
            {{ str_replace('.00', '', $awayStats->{$statField}) }}@if ($isPercentage ?? false)%@endif
        </div>
    </div>
</div>