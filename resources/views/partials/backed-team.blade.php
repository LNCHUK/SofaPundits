<x-panel>
    <h2 class="text-lg font-bold mb-4">Your Backed Team</h2>

    @if ($backedTeam)
        <div class="flex gap-x-4 mb-6">
            <img class="w-24" src="{{ $backedTeam->team->logo }}" alt="" />

            <div>
                <p class="text-2xl font-bold mt-3 mb-1 font-premier-league">{{ $backedTeam->team->name }}</p>

                <i class="fa-solid fa-trophy-star text-xl correct-score-colour drop-shadow-sm mr-1"></i>
                <i class="fa-solid fa-trophy-star text-xl correct-score-colour drop-shadow-sm mr-1"></i>
                <i class="fa-solid fa-trophy-star text-xl correct-score-colour drop-shadow-sm mr-1"></i>
                <i class="fa-solid fa-trophy-star text-xl correct-result-colour drop-shadow-sm mr-1"></i>
                <i class="fa-solid fa-trophy-star text-xl correct-result-colour drop-shadow-sm mr-1"></i>
            </div>
        </div>

        <p class="text-sm text-gray-600">3 perfect scores, 2 correct results</p>
    @else
        <p>You haven't backed a team yet.</p>
    @endif

</x-panel>