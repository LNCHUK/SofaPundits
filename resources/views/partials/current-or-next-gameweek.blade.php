<x-panel fullHeight>
    @if ($currentOrNextGameweek)
        <h2 class="text-sm font-bold mb-1 text-center">
            {{ $currentOrNextGameweek->isActive() ? 'Current' : 'Next' }} Gameweek
        </h2>

        <div class="text-center">
            <p class="text-2xl font-bold mb-1 font-premier-league">{{ $currentOrNextGameweek->name }}</p>

            <p class="text-sm text-gray-700 mb-4">
                @if ($currentOrNextGameweek->first_kick_off)
                    Kick off - {{ $currentOrNextGameweek->first_kick_off->format('jS F, h:ia') }}
                @else
                    Kick off - {{ $currentOrNextGameweek->start_date->format('jS F, h:ia') }}
                @endif
            </p>

            <div class="flex w-2/3 mx-auto mb-4">
                <div class="w-1/2 text-center">
                    <div class="uppercase font-bold text-xs text-gray-500 mb-2">
                        Matches
                    </div>

                    <span class="text-3xl font-bold text-gray-800">
                        {{ count($currentOrNextGameweek->fixtures) }}
                    </span>
                </div>
                <div class="w-1/2 text-center">
                    <div class="uppercase font-bold text-xs text-gray-500 mb-2">
                        Points
                    </div>

                    <span class="text-3xl font-bold text-gray-800">
                        {{ $currentOrNextGameweek->getPointsForActiveUser() }}
                    </span>
                </div>
            </div>

            <x-link-button :route="route('gameweeks.show', ['group' => $group, 'gameweek' => $currentOrNextGameweek])">
                View Gameweek
            </x-link-button>
        </div>
    @else
        <h2 class="text-lg font-bold mb-4">Current Gameweek</h2>

        <p>No gameweeks are active or upcoming</p>
    @endif
</x-panel>