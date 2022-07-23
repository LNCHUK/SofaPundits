<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a new Group') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="max-w-lg mb-4">
                        <x-label for="name" :value="__('Group Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <x-button>
                        {{ __('Create Group') }}
                    </x-button>
                </form>
            </x-panel>
        </x-container>
    </div>

</x-app-layout>