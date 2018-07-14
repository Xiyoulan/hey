<?php

namespace App\Policies;

use App\Http\Models\User;
use App\Http\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function destroy(User $user,Status $status){
      return $user->id ===  $status->user_id;
    }
}
