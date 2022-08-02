<div>
    Fixtures for {{ $gameweek->name }}

    <div class="flex w-full gap-x-6">
        <div class="w-1/2">
            <div class="max-w-lg mb-4">
                <x-label for="name" :value="__('Search for a team...')" />
                <x-input
                    class="block mt-1 w-full"
                    type="text"
                    wire:model="search"
                    autofocus
                />
                Searching for: {{ $search }}
            </div>

            <ul>
                @foreach ($possibleFixtures as $possibleFixture)
                    <li>
                        <div class="text-center rounded-md shadow-sm border border-gray-300 p-6 mb-4">
                            <p>
                                {{ $possibleFixture->league->name }}<br />
                                <span>{{ $possibleFixture->round }}</span>
                            </p>

                            <div class="flex gap-x-2">
                                <p class="text-right w-1/2">
                                    {{ $possibleFixture->homeTeam->name }}
                                </p>
                                <p class="text-center w-4">
                                    vs
                                </p>
                                <p class="text-left w-1/2">
                                    {{ $possibleFixture->awayTeam->name }}
                                </p>
                            </div>

                            <p>
                                {{ $possibleFixture->date->format('jS F Y - g:ia') }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="w-1/2">
            Right
        </div>
    </div>
</div>
