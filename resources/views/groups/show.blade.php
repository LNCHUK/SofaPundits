<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                Welcome to the dashboard for {{ $group->name }}
            </x-panel>
        </x-container>
    </div>

</x-app-layout>