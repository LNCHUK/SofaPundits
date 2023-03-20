<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Fixture Details
            </h2>

            <div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>

            <x-panel>
                <p class="text-center uppercase font-bold text-xs opacity-80 mb-2">
                    {{ $fixture->kick_off->format('D j M Y') }}
                    <span class="mx-1">|</span>
                    {{ $fixture->leagueSeason->league->name }}
                </p>

                <div class="flex justify-center items-center mb-2">
                    <div class="flex-1 text-right font-bold uppercase text-lg text-teal-900">
                        {{ $fixture->homeTeam->name }}
                    </div>
                    <div class="min-w-12 mx-3 flex justify-center gap-x-1">
                        <p class="font-bold text-lg border px-3 py-1.5 rounded bg-teal-600 text-white">
                            {{ (int) $fixture->home_goals }}
                        </p>
                        <p class="font-bold text-lg border px-3 py-1.5 rounded bg-teal-600 text-white">
                            {{ (int) $fixture->away_goals }}
                        </p>
                    </div>
                    <div class="flex-1 font-bold uppercase text-lg text-teal-900">
                        {{ $fixture->awayTeam->name }}
                    </div>
                </div>

                <div class="uppercase text-center text-xs text-teal-900 font-bold mb-6">
                    @if ($fixture->status_code === 'FT')
                        <span class="inline-block">Full Time</span>
                    @endif

                    @if($fixture->score['halftime'])
                        <p class="mt-1 text-center">
                            HT: {{ $fixture->score['halftime']['home'] }} - {{ $fixture->score['halftime']['away'] }}
                        </p>
                    @endif
                </div>

                <hr class="my-2 w-1/2 mx-auto">

                <div class="flex justify-center mt-3 text-xs font-bold text-teal-800 uppercase">
                    <div class="w-1/2 text-right border-r border-gray-200 pr-4 py-1">
                        {{ implode(', ', $homeGoals) }}
                    </div>
                    <div class="w-1/2 pl-4 py-1">
                        {{ implode(', ', $awayGoals) }}
                    </div>
                </div>
            </x-panel>

            <div class="h-4"></div>

            <div class="flex gap-x-4">
                <div class="w-1/3">
                    <x-panel>
                        @forelse ($fixture->events as $event)
                            @include('fixtures.partials.fixture-event', ['event' => $event, 'fixture' => $fixture])
                        @empty
                            No events found yet
                        @endforelse
                    </x-panel>
                </div>

                <div class="w-2/3">
                    <x-panel>
                        @if (count($fixture->statistics))
                            @include('fixtures.partials.stat-bar', ['title' => 'Shots on Goal', 'statField' => 'shots_on_goal'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Shots off Goal', 'statField' => 'shots_off_goal'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Total Shots', 'statField' => 'total_shots'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Blocked Shots', 'statField' => 'blocked_shots'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Shots Inside Box', 'statField' => 'shots_inside_box'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Shots Outside Box', 'statField' => 'shots_outside_box'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Fouls', 'statField' => 'fouls'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Corners', 'statField' => 'corners'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Offsides', 'statField' => 'offsides'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Possession', 'statField' => 'possession', 'isPercentage' => true])
                            @include('fixtures.partials.stat-bar', ['title' => 'Yellow Cards', 'statField' => 'yellow_cards'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Red Cards', 'statField' => 'red_cards'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Saves', 'statField' => 'saves'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Total Passes', 'statField' => 'total_passes'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Passes Completed', 'statField' => 'passes_completed'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Pass Accuracy', 'statField' => 'pass_accuracy', 'isPercentage' => true])
                            @include('fixtures.partials.stat-bar', ['title' => 'Expected Goals', 'statField' => 'expected_goals'])
                        @else
                            No statistics available yet
                        @endif
                    </x-panel>
                </div>
            </div>

        </x-container>
    </div>

</x-app-layout>