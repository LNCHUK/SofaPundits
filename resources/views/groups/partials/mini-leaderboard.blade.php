@foreach ($results as $player)
@php $player = (array) $player @endphp
    <p>
        ({{ $player['position'] }})
        - {{ $player['first_name'] . ' ' . $player['last_name'] }}
        - <strong>{{ isset($scoreKey) ? $player[$scoreKey] : $player['score'] }}</strong>
    </p>
@endforeach