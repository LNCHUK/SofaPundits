<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}: {{ __('New Gameweek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-container>
            <x-panel>
                <form action="{{ route('gameweeks.store', $group) }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="max-w-lg mb-4">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="flex gap-x-4">
                        <!-- Start Date -->
                        <div class="max-w-sm mb-4">
                            <x-label for="start_date" :value="__('Start Date')" />
                            <x-input
                                id="start_date"
                                class="block mt-1 w-full"
                                type="date"
                                name="start_date"
                                :value="old('start_date')"
                                required
                            />
                        </div>

                        <!-- End Date -->
                        <div class="max-w-sm mb-4">
                            <x-label for="end_date" :value="__('End Date')" />
                            <x-input
                                id="end_date"
                                class="block mt-1 w-full"
                                type="date"
                                name="end_date"
                                :value="old('end_date')"
                                required
                            />
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="max-w-lg mb-4">
                        <x-label for="description" :value="__('Description')" />
                        <x-textarea id="description" class="block mt-1 w-full" name="description" rows="8">
                            {{ old('description') }}
                        </x-textarea>
                    </div>

                    <x-button>
                        {{ __('Create Gameweek') }}
                    </x-button>
                </form>
            </x-panel>
        </x-container>
    </div>

</x-app-layout>