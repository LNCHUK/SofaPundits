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

            <div class="md:flex gap-x-6 gap-y-6 mb-8">
                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Total Correct Scores</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $totalCorrectResultsTable])
                </div>

                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Most Correct Scores In One Week</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $mostCorrectScoresInAWeek])
                </div>

                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Most Backed Team Correct Results</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $backedTeamWins])
                </div>
            </div>

            <div class="md:flex gap-x-6 gap-y-6 mb-8">
                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Most Weeks Won</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $mostWeeksWon, 'scoreKey' => 'weeks_won'])
                </div>

                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Most Weeks Tied</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $mostWeeksTied, 'scoreKey' => 'weeks_tied'])
                </div>

                <div class="md:w-1/3">
                    <h2 class="mb-4 text-lg font-bold">Highest Weekly Score</h2>
                    @include('groups.partials.mini-leaderboard', ['results' => $highestWeeklyScores, 'scoreKey' => 'highest_weekly_score'])
                </div>
            </div>

        </x-container>
    </div>

</x-app-layout>