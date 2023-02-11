<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{--            {{ $group->name }}--}}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>

            @feature('new-group-panels')
                <div class="md:flex gap-x-6 gap-y-6 mb-4">
                    <div class="md:w-1/3">
                        <x-panel>
                            @if ($currentOrNextGameweek)
                                <h2 class="text-lg font-bold mb-4">{{ $currentOrNextGameweek->isActive() ? 'Current' : 'Next' }} Gameweek</h2>
                            @else
                                <h2 class="text-lg font-bold mb-4">Current Gameweek</h2>
                            @endif
                        </x-panel>
                    </div>

                    <div class="md:w-1/3">
                        @include('partials.backed-team', ['backedTeam' => $backedTeam])
                    </div>
                </div>
            @endfeature

            <div class="md:flex gap-x-6 gap-y-6">
                <div class="md:w-2/3 mb-4">
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

                        <x-groups.gameweeks-table :gameweeks="$group->activeGameweeks" />

                        <h2 class="text-lg font-bold mt-8 mb-4">Upcoming Gameweeks</h2>

                        <x-groups.gameweeks-table :gameweeks="$group->upcomingGameweeks" />

                        <h2 class="text-lg font-bold mt-8 mb-4">Past Gameweeks</h2>

                        <x-groups.gameweeks-table :gameweeks="$group->pastGameweeks" />
                    </x-panel>
                </div>

                <div class="md:w-1/3">
                    <x-panel>
                        <h2 class="text-lg font-bold mb-2">Invite Others</h2>

                        <p class="text-sm mb-4">
                            Want to invite some new players? Just give them the code below and they'll
                            be able to find the group!
                        </p>

                        <p class="font-bold text-2xl text-center">{{ $group->key }}</p>
                    </x-panel>

                    <div class="h-4"></div>

                    <x-panel>
                        <h2 class="text-lg font-bold mb-4">League Table</h2>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Pos
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Player
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                            Points
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($group->getLeagueTableData() as $data)
                                    <tr>
                                        <td class="whitespace-nowrap text-left px-3 py-3 text-sm text-gray-500">
                                            {{ $data->position }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-3 text-sm text-gray-500">
                                            {{ $data->user->name }}
                                        </td>
                                        <td class="whitespace-nowrap text-left px-3 py-3 text-sm text-gray-500">
                                            {{ $data->total_points }}
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