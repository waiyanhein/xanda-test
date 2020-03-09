<?php

namespace App\Policies;

use App\Models\Spacecraft;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class SpacecraftPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Spacecraft $spacecraft)
    {
        //can only delete if the user is logged and has the fleet id as spacecraft
        return Auth::check() and $user->fleet_id == $spacecraft->fleet_id;
    }
}
