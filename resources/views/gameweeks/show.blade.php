<x-app-layout>
    @section('title', $gameweek->name . ' - ' . $group->name)

    <x-slot name="header">
        <div class="md:flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: {{ $gameweek->name }}
            </h2>

            <div>
                @can('update', $gameweek)
                    <x-link-button :route="route('gameweeks.edit', ['group' => $group, 'gameweek' => $gameweek])">
                        Edit Gameweek
                    </x-link-button>
                    <x-link-button :route="route('gameweeks.edit-fixtures', ['group' => $group, 'gameweek' => $gameweek])">
                        Edit Fixtures
                    </x-link-button>
                    <x-link-button :route="route('gameweeks.view-leaderboard', ['group' => $group, 'gameweek' => $gameweek])">
                        Leaderboard
                    </x-link-button>
                @endcan

                @can ('publish', $gameweek)
                    <form
                        action="{{ route('gameweeks.publish', ['group' => $group, 'gameweek' => $gameweek]) }}"
                        method="POST"
                        class="inline"
                        onsubmit="return confirm('Are you sure you want to publish the Gameweek? Doing this will ' +
                         'send out an email to all active users and open it up for predictions')"
                    >
                        @csrf
                        <x-buttons.success :route="route('gameweeks.publish', ['group' => $group, 'gameweek' => $gameweek])">
                            Publish Gameweek
                        </x-buttons.success>
                    </form>
                @endif

                @can ('delete', $gameweek)
                    <form
                        action="{{ route('gameweeks.destroy', ['group' => $group, 'gameweek' => $gameweek]) }}"
                        method="POST"
                        class="inline"
                        onsubmit="return confirm('Are you sure you want to delete the Gameweek? Doing this will ' +
                         'remove the Gameweek and any predictions that have been added, and cannot be reversed.')"
                    >
                        @csrf
                        @method('DELETE')

                        <x-buttons.danger>
                            Delete
                        </x-buttons.danger>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <div class="md:flex gap-x-4">
                <div class="md:w-1/4 mb-4">
                    <x-panel>

                        <div class="flex">
                            <div class="w-1/2 text-center">
                                <div class="uppercase font-bold text-xs text-gray-500 mb-4">
                                    Matches
                                </div>

                                <span class="text-5xl font-bold text-gray-800">
                                    {{ count($gameweek->fixtures) }}
                                </span>
                            </div>
                            <div class="w-1/2 text-center">
                                <div class="uppercase font-bold text-xs text-gray-500 mb-4">
                                    Points
                                </div>

                                <span class="text-5xl font-bold text-gray-800">
                                    {{ $gameweek->getPointsForActiveUser() }}
                                </span>
                            </div>
                        </div>

                        <hr class="mt-8 mb-6">

                        <h3 class="font-bold mb-4">Key:</h3>

                        <ul class="mb-4">
                            <li class="flex gap-x-2 items-center mb-4">
                                <div class="w-6 h-6 rounded incorrect-result-gradient"></div>
                                <span class="text-sm text-gray-600">Incorrect Result (0 points)</span>
                            </li>
                            <li class="flex gap-x-2 items-center mb-4">
                                <div class="w-6 h-6 rounded correct-result-gradient"></div>
                                <span class="text-sm text-gray-600">Correct Result (1 point)</span>
                            </li>
                            <li class="flex gap-x-2 items-center mb-4">
                                <div class="w-6 h-6 rounded correct-score-gradient"></div>
                                <span class="text-sm text-gray-600">Correct Score (3 points)</span>
                            </li>
                        </ul>

                        @can('updatePredictions', $gameweek)
                            <hr class="mt-8 mb-6">

                            <div class="text-center">
                                <x-link-button :route="route('gameweeks.edit-predictions', ['group' => $group, 'gameweek' => $gameweek])">
                                    Update Predictions
                                </x-link-button>
                            </div>
                        @endcan
                    </x-panel>
                </div>

                <div class="md:w-3/4">
                    <x-panel>
                        <ul class="gameweek-fixtures">
                            @forelse ($gameweek->getFixturesGroupedByDate() as $date => $fixtures)
                                <li>
                                    <span class="date">{{ $date }}</span>

                                    <ul class="fixtures">
                                        @foreach ($fixtures as $fixture)
                                            <li class="fixture">
                                                <div class="actual">
                                                    <div class="home team">
                                                        {{ $fixture->homeTeam->name }}
                                                        <img src="{{ $fixture->homeTeam->logo }}" alt="" />
                                                    </div>
                                                    <div class="score @if ($fixture->time_elapsed) has-scores @endif">
                                                        @if ($fixture->time_elapsed)
                                                            <span class="home">{{ $fixture->goals['home'] ?? '-' }}</span>
                                                            <span class="away">{{ $fixture->goals['away'] ?? '-' }}</span>
                                                        @elseif ($fixture->status === 'Match Postponed')
                                                            <span class="home">P</span>
                                                            <span class="away">P</span>
                                                        @else
                                                            {{ $fixture->kickOffTime }}
                                                        @endif
                                                    </div>
                                                    <div class="away team">
                                                        <img src="{{ $fixture->awayTeam->logo }}" alt="" />
                                                        {{ $fixture->awayTeam->name }}
                                                    </div>
                                                </div>

                                                <div class="prediction">
                                                    <span>You predicted</span>

                                                    <div class="score {{ $gameweek->getCssClassForFixturePrediction($fixture) }}">
                                                        <div class="home">
                                                            {{ $gameweek->getHomeScoreForFixturePrediction($fixture) }}
                                                        </div>
                                                        <div class="away">
                                                            {{ $gameweek->getAwayScoreForFixturePrediction($fixture) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @empty
                                <p class="bg-blue-400 font-bold p-6 text-white rounded">
                                    Looks like no fixtures have been added, click 'Edit Fixtures' above to
                                    add some fixtures to the Gameweek.
                                </p>
                            @endforelse
                        </ul>
                    </x-panel>
                </div>
            </div>
        </x-container>
    </div>

</x-app-layout>