<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                <a href="{{ route('groups.create') }}">Create a new Group</a>
            </x-panel>
        </x-container>
    </div>
</x-app-layout>
