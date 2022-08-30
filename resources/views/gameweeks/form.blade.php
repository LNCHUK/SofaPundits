<form
    action="{{ isset($gameweek) ? route('gameweeks.update', ['group' => $group, 'gameweek' => $gameweek]) : route('gameweeks.store', $group) }}"
    method="POST"
>
    @csrf
    @method(isset($gameweek) ? 'PATCH' : 'POST')

    <!-- Name -->
    <div class="max-w-lg mb-4">
        <x-label for="name" :value="__('Name')" />
        <x-input
            id="name"
            class="block mt-1 w-full"
            type="text"
            name="name"
            :value="old('name', $gameweek->name ?? '')"
            required
            autofocus
        />
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
                :value="old('start_date', isset($gameweek) ? $gameweek->start_date->format('Y-m-d') : '')"
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
                :value="old('end_date', isset($gameweek) ? $gameweek->end_date->format('Y-m-d') : '')"
                required
            />
        </div>
    </div>

    <!-- Name -->
    <div class="max-w-lg mb-4">
        <x-label for="description" :value="__('Description')" />
        <x-textarea id="description" class="block mt-1 w-full" name="description" rows="8">
            {{ old('description', $gameweek->description ?? '') }}
        </x-textarea>
    </div>

    <x-buttons.success>
        {{ isset($gameweek) ? __('Update Gameweek') : __('Create Gameweek') }}
    </x-buttons.success>
</form>