<div class="overflow-x-scroll md:overflow-hidden shadow ring-1 ring-black ring-opacity-5 ">
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                Gameweek
            </th>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                Start Date
            </th>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                End Date
            </th>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                Fixtures
            </th>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                Points
            </th>
            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                Actions
            </th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        @foreach ($gameweeks as $gameweek)
            <tr class="{{ $gameweek->isPending() && auth()->user()->id == $gameweek->group->creator()->id ? 'bg-red-50' : 'bg-white' }}">
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    {{ $gameweek->name }}
                </td>
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    {{ $gameweek->start_date->format('jS F Y') }}
                    @if ($gameweek->isUpcoming())
                        <p class="text-xs font-bold">
                            Deadline: {{ $gameweek->deadline()->diffForHumans() }}
                        </p>
                    @endif
                </td>
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    {{ $gameweek->end_date->format('jS F Y') }}
                </td>
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    {{ $gameweek->fixtures->count() }}
                </td>
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    {{ $gameweek->getPointsForActiveUser() }}
                </td>
                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                    <a href="{{ route('gameweeks.show', ['group' => $gameweek->group, 'gameweek' => $gameweek]) }}" class="text-blue-800 underline">
                        View
                    </a>

                    @if (!$gameweek->isUpcoming())
                        | <a href="{{ route('gameweeks.view-leaderboard', ['group' => $gameweek->group, 'gameweek' => $gameweek]) }}" class="text-blue-800 underline">
                            Leaderboard
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>