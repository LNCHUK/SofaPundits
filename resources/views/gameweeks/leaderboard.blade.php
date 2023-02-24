<x-app-layout>
    <x-slot name="header">
        <div class="md:flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: {{ $gameweek->name }} Leaderboard
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>

                <div class="overflow-x-auto max-w-full shadow ring-1 ring-black ring-opacity-5 ">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col" class="leaderboard-sticky px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 border-r sticky left-0 bg-white shadow-xl">
                                    Player
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 border-r">
                                    Points
                                </th>
                                @foreach ($fixtures as $fixture)
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 text-center border-r min-w-[7rem]">
                                        @if ((bool) App\Enums\UserPreference::NEW_FEATURES__ENABLE_BETA_FEATURES()->getValueForAuthenticatedUser())
                                            <a href="{{ route('fixture.detail', $fixture->id) }}">
                                        @endif
                                            <div class="flex items-center">
                                                <img src="{{ $fixture->homeTeam->logo }}" alt="" class="w-8" />
                                                <span class="mx-1">vs</span>
                                                <img src="{{ $fixture->awayTeam->logo }}" alt="" class="w-8" />
                                            </div>
                                        @if ((bool) App\Enums\UserPreference::NEW_FEATURES__ENABLE_BETA_FEATURES()->getValueForAuthenticatedUser())
                                            </a>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td class="leaderboard-sticky whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500 border-r sticky left-0 bg-white shadow-xl">
                                    <span class="font-bold">Results</span>
                                </td>
                                <td class="border-r"></td>
                                @foreach ($fixtures as $fixture)
                                    <td class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 text-center border-r min-w-[7rem]">
                                        <span class="font-bold">
                                            {{ $fixture->home_goals }} - {{ $fixture->away_goals }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                            @foreach ($gameweek->getPlayersOrderedByPoints() as $player)
                                <tr @if ($player->is(auth()->user())) class="bg-blue-50" @endif>
                                    <td class="leaderboard-sticky sm:whitespace-nowrap text-left px-3 py-4 sm:text-sm text-gray-500 border-r sticky left-0 bg-white shadow-xl text-xs">
                                        {{ $player->name }}
                                        @can('updatePredictionsForUser', [$gameweek, $player])
                                            <p class="text-xs">
                                                <a class="underline text-blue-800" href="{{ route('gameweeks.edit-user-predictions', ['group' => $group, 'gameweek' => $gameweek, 'user' => $player]) }}">
                                                    Edit Predictions
                                                </a>
                                            </p>
                                        @endcan
                                    </td>
                                    <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500 border-r text-center">
                                        {{ $gameweek->getPointsForUser($player) }}
                                    </td>
                                    @foreach ($fixtures as $fixture)
                                        <td scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 text-center border-r min-w-[7rem]">
                                            @if ($prediction = $gameweek->getUserPredictionForFixture($player, $fixture))
                                                <div class="gameweek-fixtures">
                                                    <div class="fixture !border-0 !p-0 justify-center">
                                                        <div class="prediction !w-[auto]">
                                                            <div class="score {{ $gameweek->getCssClassForFixturePrediction($fixture, $player) }}">
                                                                <div class="home">
                                                                    {{ $gameweek->getHomeScoreForFixturePrediction($fixture, $player) }}
                                                                </div>
                                                                <div class="away">
                                                                    {{ $gameweek->getAwayScoreForFixturePrediction($fixture, $player) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                - vs -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </x-panel>
        </x-container>
    </div>

</x-app-layout>