@foreach ($results as $player)
    <p>
        ({{ $player->position }})
        - {{ $player->first_name . ' ' . $player->last_name }}
        - <strong>{{ $player->score }}</strong>
    </p>
@endforeach