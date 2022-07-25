<div class="p-4 pb-8 text-center">
    <div class="pt-6 py-4">
        <h2 class="text-2xl font-bold mb-4">Join an Existing Group</h2>
    </div>

    <p class="mb-4">Enter a group code to join an existing group</p>

    <div class="mb-4">
        <input
            type="text"
            class="rounded-lg border-gray-500 px-6 py-4 text-center"
            wire:model="groupKey"
            wire:keyup.debounce.150ms="findGroups"
        />
    </div>

    @if (is_null($groups))
        <p class="font-bold">Enter a code to find groups</p>
    @elseif (count($groups))
        <div class="mt-8 px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Group Name</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Players</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Join</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($groups as $group)
                        <tr>
                            <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                {{ $group->name }}
                            </td>
                            <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                {{ $group->numberOfPlayers() }}
                            </td>
                            <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">
                                <form action="{{ route('groups.join', $group) }}" method="post">
                                    @csrf
                                    <x-button>Join</x-button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="font-bold">No groups found</p>
    @endif
</div>
