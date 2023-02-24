<div class="flex items-center @if ($side == 'home') justify-end @endif gap-x-2">
    @includeWhen($side === 'home', 'fixtures.partials.fixture-event-text')

    @if ($event->type == 'Goal')
        <img src="/images/icons/football.svg" alt="Goal" class="inline w-5" />
    @elseif ($event->type == 'Card')
        @if ($event->detail == 'Yellow Card')
            <img src="/images/icons/yellow-card.svg" alt="Goal" class="inline w-5 drop-shadow-sm" />
        @elseif ($event->detail == 'Red Card')
            <img src="/images/icons/red-card.svg" alt="Goal" class="inline w-5 drop-shadow-sm" />
        @elseif ($event->detail == 'Second Yellow Card')
            <img src="/images/icons/red-yellow-card.svg" alt="Goal" class="inline w-5 drop-shadow-sm" />
        @endif
    @elseif ($event->type == 'subst')
        <img src="/images/icons/substitution.svg" alt="Goal" class="inline w-5 drop-shadow-sm" />
    @endif

    @includeWhen($side === 'away', 'fixtures.partials.fixture-event-text')
</div>