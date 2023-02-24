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

            <div class="flex gap-x-4">
                <div class="w-1/3">
                    <x-panel>
                        <div class="flex justify-between">
                            <img src="{{ $fixture->homeTeam->logo }}" alt="" />
                            <img src="{{ $fixture->awayTeam->logo }}" alt="" />
                        </div>
                    </x-panel>

                    <div class="h-4"></div>

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
    {{--                        @include('fixtures.partials.stat-bar', ['title' => 'Red Cards', 'statField' => 'red_cards'])--}}
                            @include('fixtures.partials.stat-bar', ['title' => 'Saves', 'statField' => 'saves'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Total Passes', 'statField' => 'total_passes'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Passes Completed', 'statField' => 'passes_completed'])
                            @include('fixtures.partials.stat-bar', ['title' => 'Pass Accuracy', 'statField' => 'pass_accuracy', 'isPercentage' => true])
    {{--                        @include('fixtures.partials.stat-bar', ['title' => 'Expected Goals', 'statField' => 'expected_goals'])--}}
                        @else
                            No statistics available yet
                        @endif
                    </x-panel>
                </div>
            </div>

        </x-container>
    </div>

</x-app-layout>