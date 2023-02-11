<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: Players
            </h2>

            <div>
                <x-buttons.success form="group-players">
                    Update Players
                </x-buttons.success>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>

                <form id="group-players" action="{{ route('groups.players.update', $group) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Player
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Email
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Backed Team
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($group->users as $player)
                                <tr>
                                    <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                        {{ $player->name }}
                                    </td>
                                    <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                        {{ $player->email }}
                                    </td>
                                    <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                        <x-select name="backed_team[{{ $player->id }}]">
                                            <option value="">No backed team</option>
                                            @foreach ($teams as $id => $team)
                                                <option value="{{ $id }}" @if(optional($group->getBackedTeamForUser($player))->team_id == $id) selected @endif>
                                                    {{ $team }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100" class="text-center">
                                        No players found in this group
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>

            </x-panel>
        </x-container>
    </div>

</x-app-layout>