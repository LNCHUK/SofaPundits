<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}: {{ $gameweek->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                Gameweek details

                <livewire:gameweek-fixtures-manager :gameweekId="$gameweek->id" />
            </x-panel>
        </x-container>
    </div>

</x-app-layout>