<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return Response|bool
     */
    public function view(User $user, User $model): Response|bool
    {
        // TODO: Allow hiding profiles, for now every profile is public
        return $user->can('view profile');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return Response|bool
     */
    public function update(User $user, User $model): Response|bool
    {
        return $user->can('edit profile') && $user->id === $model->id;
    }
}
