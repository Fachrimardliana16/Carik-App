<?php

namespace App\Policies;

use App\Models\Tembusan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TembusanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tembusan');
    }

    public function view(User $user, Tembusan $tembusan): bool
    {
        return $user->can('view_tembusan');
    }

    public function create(User $user): bool
    {
        return $user->can('create_tembusan');
    }

    public function update(User $user, Tembusan $tembusan): bool
    {
        return $user->can('update_tembusan');
    }

    public function delete(User $user, Tembusan $tembusan): bool
    {
        return $user->can('delete_tembusan');
    }

    public function restore(User $user, Tembusan $tembusan): bool
    {
        return $user->can('restore_tembusan');
    }

    public function forceDelete(User $user, Tembusan $tembusan): bool
    {
        return $user->can('force_delete_tembusan');
    }
}
