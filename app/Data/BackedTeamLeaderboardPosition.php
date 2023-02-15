<?php

namespace App\Data;

use App\Models\ApiFootball\Team;
use App\Models\User;

class BackedTeamLeaderboardPosition
{
    public function __construct(
        private User $user,
        private Team $team,
        private int $correct_scores,
        private int $position,
    ) {}

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return int
     */
    public function getCorrectScores(): int
    {
        return $this->correct_scores;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}