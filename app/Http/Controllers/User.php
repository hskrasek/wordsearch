<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class User extends Controller
{
    public function __invoke(Request $request, \App\Models\User $user): UserResource
    {
        return new UserResource($user);
    }
}
