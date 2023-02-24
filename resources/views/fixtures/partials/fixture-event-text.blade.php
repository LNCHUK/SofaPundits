@if ($event->type === 'subst')
    <div class="flex flex-col">
        <span class="text-[0.6rem] uppercase opacity-80">{{ $event->player_name }} (off)</span>
        <span class="text-[0.6rem] uppercase opacity-80">{{ $event->secondary_player_name }} (on)</span>
    </div>
@elseif ($event->type === 'Goal' && $event->secondary_player_name)
    <div class="flex flex-col">
        <span class="text-[0.6rem] uppercase opacity-80">{{ $event->player_name }}</span>
        <span class="text-[0.6rem] uppercase opacity-60 italic">{{ $event->secondary_player_name }}</span>
    </div>
@elseif ($event->type === 'Card' && $event->comments)
    <div class="flex flex-col">
        <span class="text-[0.6rem] uppercase opacity-80">{{ $event->player_name }}</span>
        <span class="text-[0.6rem] uppercase opacity-60">{{ $event->comments }}</span>
    </div>
@else
    <span class="text-[0.6rem] uppercase opacity-80">{{ $event->player_name }}</span>
@endif