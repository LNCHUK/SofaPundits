<x-app-layout>
    @section('title', 'Edit Gameweek - ' . $group->name)

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}: {{ __('Edit Gameweek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                @include('gameweeks.form')
            </x-panel>
        </x-container>
    </div>

</x-app-layout>