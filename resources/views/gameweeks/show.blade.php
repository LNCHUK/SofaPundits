<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: {{ $gameweek->name }}
            </h2>

            <div>
                @can('update', $gameweek)
                    <x-link-button :route="route('gameweeks.edit', ['group' => $group, 'gameweek' => $gameweek])">
                        Edit Gameweek
                    </x-link-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                <livewire:gameweek-fixtures-manager :gameweekId="$gameweek->id" />
            </x-panel>
        </x-container>
    </div>

</x-app-layout>