<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: Leaderboards
            </h2>

            <div>
                @can('update', $group)
                    <x-link-button :route="route('groups.players', $group)">
                        Players
                    </x-link-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>

            <div class="md:flex gap-x-6 gap-y-6 mb-4">
                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Total Correct Scores</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $totalCorrectResultsTable])
                </div>

                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Most Correct Scores In One Week</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $mostCorrectScoresInAWeek])
                </div>

                <div class="md:w-1/3">
                    Right
                </div>
            </div>

        </x-container>
    </div>

</x-app-layout>