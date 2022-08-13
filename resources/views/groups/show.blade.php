<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <div class="flex gap-x-6 gap-y-6">
                <div class="w-2/3">
                    <x-panel>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-bold">Active Gameweeks</h2>

                            <div>
                                @can('createGameweekForGroup', $group)
                                    <x-link-button :route="route('gameweeks.create', ['group' => $group])">
                                        Create Gameweek
                                    </x-link-button>
                                @endcan
                            </div>
                        </div>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
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
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($group->getActiveGameweeks() as $gameweek)
                                    <tr>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->id }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->start_date->format('jS F Y') }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->end_date->format('jS F Y') }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->fixtures->count() }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            <a href="{{ route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]) }}" class="text-blue-800 underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h2 class="text-lg font-bold mt-8 mb-4">Past Gameweeks</h2>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
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
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($group->pastGameweeks as $gameweek)
                                    <tr>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->id }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->start_date->format('jS F Y') }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->end_date->format('jS F Y') }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $gameweek->fixtures->count() }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            <a href="{{ route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]) }}" class="text-blue-800 underline">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-panel>
                </div>

                <div class="w-1/3">
                    <x-panel>
                        <h2 class="text-lg font-bold mb-4">Players</h2>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
                            <table class="min-w-full divide-y divide-gray-300">
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($group->users as $player)
                                    <tr>
                                        <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                            {{ $player->name }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-panel>
                </div>
            </div>

        </x-container>
    </div>

</x-app-layout>