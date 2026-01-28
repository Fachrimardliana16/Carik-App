<?php

namespace App\Policies;

use App\Models\Splaner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SplanerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_splaner');
    }

    public function view(User $user, Splaner $splaner): bool
    {
        return $user->can('view_splaner');
    }

    public function create(User $user): bool
    {
        return $user->can('create_splaner');
    }

    public function update(User $user, Splaner $splaner): bool
    {
        return $user->can('update_splaner');
    }

    public function delete(User $user, Splaner $splaner): bool
    {
        return $user->can('delete_splaner');
    }

    public function restore(User $user, Splaner $splaner): bool
    {
        return $user->can('restore_splaner');
    }

    public function forceDelete(User $user, Splaner $splaner): bool
    {
        return $user->can('force_delete_splaner');
    }
}
