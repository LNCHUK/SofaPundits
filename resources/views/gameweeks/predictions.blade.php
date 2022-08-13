<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: {{ $gameweek->name }}
            </h2>

            <div>
                @can('update', $gameweek)
                    <x-link-button :route="route('gameweeks.edit', ['group' => $group, 'gameweek' => $gameweek])">
                        Edit Gameweek
                    </x-link-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">

        <form action="{{ route('gameweeks.update-predictions', ['group' => $gameweek->group, 'gameweek' => $gameweek]) }}" method="POST">
            @csrf
            @method('POST')

            <x-container>
                <x-panel>
                    <ul class="gameweek-fixtures predictions">
                        @foreach ($gameweek->getFixturesGroupedByDate() as $date => $fixtures)
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
                                                <div
                                                    class="user-prediction @if ($fixture->time_elapsed) has-scores @endif @if ($errors->has('fixtures.'.$fixture->id.'.*')) has-error @endif"
                                                >
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        name="fixtures[{{ $fixture->id }}][home_score]"
                                                        @if (is_array(old('fixtures')))
                                                            value="{{ isset(old('fixtures')[$fixture->id]) ? old('fixtures')[$fixture->id]['home_score'] : '' }}"
                                                        @else
                                                            value="{{ $gameweek->activeUserPredictions->firstWhere('fixture_id', $fixture->id)->home_score }}"
                                                        @endif
                                                    />

                                                    <input
                                                        type="number"
                                                        min="0"
                                                        name="fixtures[{{ $fixture->id }}][away_score]"
                                                        @if (is_array(old('fixtures')))
                                                            value="{{ isset(old('fixtures')[$fixture->id]) ? old('fixtures')[$fixture->id]['away_score'] : '' }}"
                                                        @else
                                                            value="{{ $gameweek->activeUserPredictions->firstWhere('fixture_id', $fixture->id)->away_score }}"
                                                        @endif
                                                    />
                                                </div>
                                                <div class="away team">
                                                    <img src="{{ $fixture->awayTeam->logo }}" alt="" />
                                                    {{ $fixture->awayTeam->name }}
                                                </div>
                                            </div>
                                            @if ($errors->has('fixtures.'.$fixture->id.'.*'))
                                                <div class="error">
                                                    {{ $errors->first("fixtures.{$fixture->id}.*") }}
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>

                    <x-submit-button>
                        Save Predictions
                    </x-submit-button>
                </x-panel>
            </x-container>

        </form>
    </div>

</x-app-layout>