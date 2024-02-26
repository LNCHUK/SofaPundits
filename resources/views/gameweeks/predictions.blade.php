<x-app-layout>
    @section('title', 'Update Predictions - ' . $gameweek->name)

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

        <form
            action="{{ isset($user)
                ? route('gameweeks.update-user-predictions', ['group' => $gameweek->group, 'gameweek' => $gameweek, 'user' => $user])
                : route('gameweeks.update-predictions', ['group' => $gameweek->group, 'gameweek' => $gameweek]) }}"
            method="POST"
        >
            @csrf
            @method('POST')

            <x-container>
                <x-panel>
                    <ul class="gameweek-fixtures predictions">
                        @php ($tabIndex = 1)
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
                                                        data-prediction-field
                                                        name="fixtures[{{ $fixture->id }}][home_score]"
                                                        @if (is_array(old('fixtures')))
                                                            value="{{ isset(old('fixtures')[$fixture->id]) ? old('fixtures')[$fixture->id]['home_score'] : '' }}"
                                                        @else
                                                            value="{{ $gameweek->getHomeScoreForFixturePrediction($fixture, $user ?? auth()->user()) }}"
                                                        @endif
                                                        tabindex="{{ $tabIndex }}"
                                                    />
                                                    @php ($tabIndex++)

                                                    <input
                                                        type="number"
                                                        min="0"
                                                        data-prediction-field
                                                        name="fixtures[{{ $fixture->id }}][away_score]"
                                                        @if (is_array(old('fixtures')))
                                                            value="{{ isset(old('fixtures')[$fixture->id]) ? old('fixtures')[$fixture->id]['away_score'] : '' }}"
                                                        @else
                                                            value="{{ $gameweek->getAwayScoreForFixturePrediction($fixture, $user ?? auth()->user()) }}"
                                                        @endif
                                                        tabindex="{{ $tabIndex }}"
                                                        @php ($tabIndex++)
                                                    />
                                                </div>
                                                <div class="away team">
                                                    <img src="{{ $fixture->awayTeam->logo }}" alt="" />
                                                    {{ $fixture->awayTeam->name }}
                                                </div>
                                            </div>
                                            <div class="flex justify-center items-center mx-auto mt-2">
                                                @include('partials.team-form', ['form' => substr($fixture->home_team_form, -5)])
                                                <div class="mx-12 uppercase text-xs text-gray-600">Form</div>
                                                @include('partials.team-form', ['form' => substr($fixture->away_team_form, -5)])
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