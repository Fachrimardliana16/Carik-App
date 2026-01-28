<?php

namespace App\Policies;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_activity::log');
    }

    public function view(User $user, Activity $activity): bool
    {
        return $user->can('view_activity::log');
    }

    public function create(User $user): bool
    {
        return $user->can('create_activity::log');
    }

    public function update(User $user, Activity $activity): bool
    {
        return $user->can('update_activity::log');
    }

    public function delete(User $user, Activity $activity): bool
    {
        return $user->can('delete_activity::log');
    }

    public function restore(User $user, Activity $activity): bool
    {
        return $user->can('restore_activity::log');
    }

    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->can('force_delete_activity::log');
    }
}
