<div
    {{ $attributes->except(['class']) }}
    class="fixture-card {{ $attributes['class'] ?? '' }}"
>
    <p class="meta season">
        {{ $fixture->league->name }} - <span>{{ $fixture->round }}</span>
    </p>

    <div class="teams">
        <p class="text-right w-1/2">
            <span class="team-name">{{ $fixture->homeTeam->name }}</span>
            <img src="{{ $fixture->homeTeam->logo }}" alt="" />
        </p>
        <p class="text-center w-4">
            vs
        </p>
        <p class="text-left w-1/2">
            <img src="{{ $fixture->awayTeam->logo }}"  alt="" />
            <span class="team-name">{{ $fixture->awayTeam->name }}</span>
        </p>
    </div>

    <p class="meta date">
        {{ $fixture->date->format('jS F Y - g:ia') }}
    </p>
</div>