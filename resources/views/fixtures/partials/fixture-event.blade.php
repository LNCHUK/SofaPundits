<div class="relative">
    <div class="flex py-4 items-center">
        <div class="w-1/2 text-right pr-3">
            @if ($event->team_id == $fixture->home_team_id)
                @include('fixtures.partials.fixture-event-detail', ['side' => 'home'])
            @endif
        </div>
        <div class="pt-[0.7em] pr-[0.75em] pb-[0.5em] pl-[0.8em] bg-[#4a4a88] text-center text-white rounded-[0.4rem] drop-shadow font-bold leading-none text-[0.65rem] z-10 grid place-content-center">
            <span class="whitespace-nowrap">{{ $event->minutes_elapsed }}' @if ($event->extra_minutes_elapsed) +{{$event->extra_minutes_elapsed}}@endif</span>
        </div>
        <div class="w-1/2 pl-3">
            @if ($event->team_id == $fixture->away_team_id)
                @include('fixtures.partials.fixture-event-detail', ['side' => 'away'])
            @endif
        </div>
    </div>

    <div class="w-[1px] absolute top-0 bottom-0 bg-gray-200 z-0 left-1/2"></div>
</div>