<div>
    <div class="flex w-full gap-x-6">
        <div class="w-1/2">
            <h2 class="text-lg font-bold mb-4">Find Fixtures</h2>

            <div class="mb-4">
                <x-label for="name" :value="__('Search for a team...')" />
                <x-input
                    class="block mt-1 w-full"
                    type="search"
                    wire:model="search"
                    autofocus
                />
            </div>

            <p class="text-sm font-semibold text-center mb-4">
                Click on a result to select the fixture for this gameweek
            </p>

            <ul>
                @foreach ($possibleFixtures as $possibleFixture)
                    <li>
                        <x-fixtures.card
                            :fixture="$possibleFixture"
                            wire:key="gameweek_{{ $gameweekId }}-possibleFixture_{{ $possibleFixture->id }}"
                            wire:click="selectFixture({{ $possibleFixture->id }})"
                        />
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="w-1/2">
            <h2 class="text-lg font-bold mb-4">Chosen Fixtures</h2>

            <ul>
                @forelse ($chosenFixtures as $chosenFixture)
                    <li>
                        <x-fixtures.card
                            :fixture="$chosenFixture"
                            wire:key="gameweek_{{ $gameweekId }}-chosenFixture_{{ $chosenFixture->id }}"
                            wire:click="removeSelectedFixture({{ $chosenFixture->id }})"
                        />
                    </li>
                @empty
                    <p class="text-sm font-semibold text-center mb-4">
                        Select some fixtures on the left to add them to this gameweek
                    </p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
