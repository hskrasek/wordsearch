<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return null|bool
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Game $game
     *
     * @return Response|bool
     */
    public function view(User $user, Game $game): Response|bool
    {
        return $game->player()->is($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->can('play games');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Game $game
     *
     * @return Response|bool
     */
    public function update(User $user, Game $game): Response|bool
    {
        return $user->can('play games') && $game->player->is($user);
    }
}
