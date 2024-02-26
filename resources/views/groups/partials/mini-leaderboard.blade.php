<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5">
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                    Pos
                </th>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                    Player
                </th>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                    Score
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @foreach ($results as $player)
            @php $player = (array) $player @endphp
                <tr @if($player['position'] === 1) class="bg-gradient-to-l from-orange-100 to-transparent" @endif>
                    <td class="whitespace-nowrap text-center px-2 py-2 text-sm text-gray-500">
                        <span class="font-premier-league text-lg @if ($player['position'] === 1) correct-score-colour @endif drop-shadow-sm">
                            {{ $player['position'] }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap font-premier-league text-left px-3 py-3 text-sm text-gray-500">
                        {{ $player['first_name'] . ' ' . $player['last_name'] }}
                    </td>
                    <td class="whitespace-nowrap font-premier-league text-center px-3 py-3 text-sm text-gray-500">
                        <strong>{{ isset($scoreKey) ? $player[$scoreKey] : $player['score'] }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
