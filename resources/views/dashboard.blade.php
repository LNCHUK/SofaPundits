<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <div class="flex gap-x-4 items-start justify-center">
                <div class="w-1/3 text-center">
                    <x-panel>
                        <h2 class="text-2xl font-bold mb-4">Create a new Group</h2>
                        <a
                            href="{{ route('groups.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            Create a new Group
                        </a>
                    </x-panel>
                </div>

                @if (auth()->user()->isInAtLeastOneGroup())
                    <div class="w-1/3 text-center">
                        <x-panel>
                            <h2 class="text-2xl font-bold mb-6">Your Current Groups</h2>

                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Group Name</th>
                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Players</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach (auth()->user()->groups as $group)
                                            <tr>
                                                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">{{ $group->name }}</td>
                                                <td class="whitespace-nowrap text-left px-3 py-4 text-sm text-gray-500">{{ $group->numberOfPlayers() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </x-panel>
                    </div>
                @endif

                <div class="w-1/3 text-center">
                    <x-panel>
                        <h2 class="text-2xl font-bold mb-4">Join an Existing Group</h2>
                        <a href="{{ route('groups.create') }}">Create a new Group</a>
                    </x-panel>
                </div>
            </div>
        </x-container>
    </div>
</x-app-layout>
