<div class="mb-6">
    <div class="flex w-full gap-x-6">
        <div class="w-1/2">
            <x-panel>
                <h2 class="text-lg font-bold mb-4">Find Fixtures</h2>

                <div class="flex gap-x-2 mb-4">
                    <div class="w-1/3">
                        <x-label for="status" :value="__('League')" />
                        <x-select
                            class="block mt-1 w-full"
                            wire:key="gameweekFixtureManager_leagueSelector"
                            wire:model="leagueId"
                            :options="$leagues"
                        />
                    </div>

                    <div class="w-2/3">
                        <x-label for="name" :value="__('Search for a team...')" />
                        <x-input
                            class="block mt-1 w-full"
                            type="search"
                            wire:model="search"
                            autofocus
                        />
                    </div>
                </div>

                <p class="text-sm font-semibold text-center mb-4">
                    Click on a result to select the fixture for this gameweek
                </p>

                <div class="overflow-y-auto max-h-96 border border-gray-200 p-4">
                    <ul>
                        @forelse ($possibleFixtures as $possibleFixture)
                            <li>
                                <x-fixtures.card
                                    :fixture="$possibleFixture"
                                    wire:key="gameweek_{{ $gameweekId }}-possibleFixture_{{ $possibleFixture->id }}"
                                    wire:click="selectFixture({{ $possibleFixture->id }})"
                                />
                            </li>
                        @empty
                            <p class="text-sm font-semibold text-center">
                                No fixtures available
                            </p>
                        @endforelse
                    </ul>
                </div>
            </x-panel>
        </div>

        <div class="w-1/2">
            <x-panel>
                <h2 class="text-lg font-bold mb-4">Chosen Fixtures</h2>

                <p class="text-sm font-semibold text-center mb-4">
                    Click on a fixture to <strong>remove</strong> it from this gameweek.
                </p>

                <div class="overflow-y-auto max-h-[29rem] border border-gray-200 p-4">
                    <ul>
                        @forelse ($chosenFixtures as $chosenFixture)
                            <li>
                                <x-fixtures.card
                                    :fixture="$chosenFixture"
                                    wire:key="gameweek_{{ $gameweekId }}-chosenFixture_{{ $chosenFixture->id }}"
                                    wire:click="removeSelectedFixture({{ $chosenFixture->id }})"
                                />
                                <input type="hidden" name="selected_fixtures[]" value="{{ $chosenFixture->id }}" />
                            </li>
                        @empty
                            <p class="text-sm font-semibold text-center mb-4">
                                Select some fixtures on the left to add them to this gameweek
                            </p>
                        @endforelse
                    </ul>
                </div>
            </x-panel>
        </div>
    </div>
</div>
