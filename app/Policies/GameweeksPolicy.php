<?php

namespace App\Policies;

use App\Models\Gameweek;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameweeksPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Gameweek  $gameweek
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Gameweek $gameweek)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Gameweek  $gameweek
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Gameweek $gameweek)
    {
        return $user->isCreatorOf($gameweek->group);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Gameweek  $gameweek
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Gameweek $gameweek)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Gameweek  $gameweek
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Gameweek $gameweek)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Gameweek  $gameweek
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Gameweek $gameweek)
    {
        //
    }

    /**
     * Determine whether the user can update predictions for this gameweek.
     *
     * @param User $user
     * @param Gameweek $gameweek
     * @return bool
     */
    public function updatePredictions(User $user, Gameweek $gameweek)
    {
        // If the user is the owner, or if the Gameweek is upcoming
        return $user->isCreatorOf($gameweek->group)
            || $gameweek->isUpcoming();
    }
}
