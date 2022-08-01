<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: Gameweeks
            </h2>

            <div>
                <x-link-button :route="route('gameweeks.create', $group)">
                    Create Gameweek
                </x-link-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                List of all gameweeks for this group
            </x-panel>
        </x-container>
    </div>

</x-app-layout>