<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Preferences
            </h2>

            <div>
{{--                @can('update', $group)--}}
                <x-buttons.success form="preferences-form">
                    Save Preferences
                </x-buttons.success>
{{--                @endcan--}}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <x-container>

            <form action="#" id="preferences-form" method="POST">
                @csrf()

                @foreach ($userPreferences as $category => $preferences)
                    <h2 class="text-lg font-bold mb-4">
                        {{ $category }}
                    </h2>

                    <x-panel>
                        @foreach ($preferences as $preference)
                            <input type="hidden" name="expected_preferences[{{ $preference->type }}][]" value="{{ $preference->slug }}" />

                            <div class="sm:flex justify-between items-start pb-8 mb-8 border-b border-gray-200 last:border-0 last:mb-0 last:pb-2">
                                <div>
                                    <p class="font-bold mb-2 text-gray-700">
                                        {{ $preference->title }}
                                    </p>
                                    <p class="text-sm text-gray-900 max-w-3xl">
                                        {!! $preference->description !!}
                                    </p>
                                </div>

                                <div class="pt-4 sm:ml-8">
                                    @include('partials.preferences-control')
                                </div>
                            </div>
                        @endforeach
                    </x-panel>

                    <div class="h-12"></div>
                @endforeach


            </form>

        </x-container>
    </div>

</x-app-layout>