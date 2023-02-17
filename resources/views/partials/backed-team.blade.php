<x-panel fullHeight>
    <h2 class="text-sm font-bold mb-4 text-center">
        Your Backed Team
    </h2>

    @if ($backedTeam)
        <div class="flex justify-center items-center gap-x-4 mb-6">
            <img class="w-28" src="{{ $backedTeam->team->logo }}" alt="" />

            <div>
                <p class="text-2xl font-bold mb-1 font-premier-league">{{ $backedTeam->team->name }}</p>

                <div class="">
                    @for ($i = 0; $i < optional($backedTeam->leaderboardData)->correct_scores ?? 0; $i++)
                        <i class="fa-solid fa-trophy-star text-xl correct-score-colour drop-shadow-sm"></i>
                    @endfor
{{--                    <i class="fa-solid fa-trophy-star text-lg correct-result-colour drop-shadow-sm -ml-2"></i>--}}
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-600 text-center">
            {{ optional($backedTeam->leaderboardData)->correct_scores ?? 0 }} perfect scores
        </p>
    @else
        <p class="text-center">You haven't backed a team yet.</p>
    @endif

</x-panel>