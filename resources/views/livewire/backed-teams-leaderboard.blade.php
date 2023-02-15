<x-panel>
    <h2 class="text-lg font-bold mb-4">Backed Teams Leaderboard</h2>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                        Pos
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                        Team
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                        Player
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                        Results
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($leaderboardData as $data)
                    <tr @if($data->getPosition() === 1) class="bg-gradient-to-l from-orange-100 to-transparent" @endif>
                        <td class="whitespace-nowrap text-center px-1 py-1 text-sm text-gray-500">
                            <span class="font-premier-league text-lg @if ($data->getPosition() === 1) correct-score-colour @endif drop-shadow-sm">
                                {{ $data->getPosition() }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap text-center px-3 py-3 text-sm text-gray-500">
                            <img src="{{ $data->getTeam()->logo }}"
                                 alt="{{ $data->getTeam()->name }}"
                                 class="w-6 inline-block"
                            />
                        </td>
                        <td class="whitespace-nowrap font-premier-league text-left px-3 py-3 text-sm text-gray-500">
                            {{ $data->getUser()->name }}
                        </td>
                        <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                            {{ $data->getCorrectScores() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-panel>