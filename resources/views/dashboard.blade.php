<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <div class="flex gap-x-4">
                <div class="w-1/3 text-center">
                    <x-panel>
                        <h2 class="text-2xl font-bold mb-4">Create a new Group</h2>
                        <a href="{{ route('groups.create') }}">Create a new Group</a>
                    </x-panel>
                </div>

                <div class="w-1/3 text-center">
                    <x-panel>
                        <h2 class="text-2xl font-bold mb-4">Your Current Groups</h2>
                        <a href="{{ route('groups.create') }}">Create a new Group</a>
                    </x-panel>
                </div>

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
