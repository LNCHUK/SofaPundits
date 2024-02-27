<x-app-layout>
    @section('title', 'Leaderboards - ' . $group->name)

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

            <div class="mb-12">
                <h2 class="mb-4 text-lg font-bold">Final Leaderboard</h2>
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-[5rem] px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Pos
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Player
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Total Score
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Correct Scores
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Max 3pts in a Week
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Backed Team Results
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Weeks Won
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Weeks Tied
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                Max Week Score
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($group->getLeagueTableData() as $data)
                        <tr @if($data->position === 1) class="bg-gradient-to-l from-orange-100 to-transparent" @endif>
                            <td class="whitespace-nowrap text-center px-2 py-2 text-sm text-gray-500">
                                <span class="font-premier-league text-lg @if ($data->position === 1) correct-score-colour @endif drop-shadow-sm">
                                    {{ $data->position }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-left px-3 py-3 text-sm text-gray-500">
                                {{ $data->user->name }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $data->total_points }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $totalCorrectResultsTable->firstWhere('user_id', $data->user->id)?->score }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $mostCorrectScoresInAWeek->firstWhere('user_id', $data->user->id)?->score }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $backedTeamWins->get($data->user->id)['score'] ?? 0 }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $mostWeeksWon->firstWhere('id', $data->user->id)['weeks_won'] }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $mostWeeksTied->firstWhere('id', $data->user->id)['weeks_tied'] }}
                            </td>
                            <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                                {{ $highestWeeklyScores->firstWhere('id', $data->user->id)['highest_weekly_score'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

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