<x-app-layout>
    @section('title', 'Edit Fixtures - ' . $gameweek->name)

    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}: {{ __('Edit Gameweek Fixtures') }}
            </h2>

            <div>
                <x-buttons.success form="edit-gameweek-fixtures">
                    {{ isset($gameweek) ? __('Update Gameweek') : __('Create Gameweek') }}
                </x-buttons.success>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>
            <form
                id="edit-gameweek-fixtures"
                action="{{ route('gameweeks.update-fixtures', ['group' => $group, 'gameweek' => $gameweek]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <livewire:gameweek-fixtures-manager :gameweekId="$gameweek->id" />
            </form>
        </x-container>
    </div>

</x-app-layout>