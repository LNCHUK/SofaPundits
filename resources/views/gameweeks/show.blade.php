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
        <x-container>
            <div class="flex gap-x-4">
                <div class="w-1/4">
                    <x-panel>
                        Details
                    </x-panel>
                </div>

                <div class="w-3/4">
                    <x-panel>
                        <ul class="gameweek-fixtures">
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
                                                    <div class="score @if ($fixture->time_elapsed) has-scores @endif">
                                                        @if ($fixture->time_elapsed)
                                                            <span class="home">{{ $fixture->goals['home'] ?? '-' }}</span>
                                                            <span class="away">{{ $fixture->goals['away'] ?? '-' }}</span>
                                                        @else
                                                            {{ $fixture->kick_off->format('h:i') }}
                                                        @endif
                                                    </div>
                                                    <div class="away team">
                                                        <img src="{{ $fixture->awayTeam->logo }}" alt="" />
                                                        {{ $fixture->awayTeam->name }}
                                                    </div>
                                                </div>

                                                <div class="prediction">
                                                    <span>You predicted</span>

                                                    <div class="score">
                                                        <div class="home">1</div>
                                                        <div class="away">2</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </x-panel>
                </div>
            </div>
        </x-container>
    </div>

</x-app-layout>