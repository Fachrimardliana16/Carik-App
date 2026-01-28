<?php

namespace App\Policies;

use App\Models\ArsipDigital;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArsipDigitalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_arsip_digital');
    }

    public function view(User $user, ArsipDigital $arsipDigital): bool
    {
        return $user->can('view_arsip_digital');
    }

    public function create(User $user): bool
    {
        return $user->can('create_arsip_digital');
    }

    public function update(User $user, ArsipDigital $arsipDigital): bool
    {
        return $user->can('update_arsip_digital');
    }

    public function delete(User $user, ArsipDigital $arsipDigital): bool
    {
        return $user->can('delete_arsip_digital');
    }

    public function restore(User $user, ArsipDigital $arsipDigital): bool
    {
        return $user->can('restore_arsip_digital');
    }

    public function forceDelete(User $user, ArsipDigital $arsipDigital): bool
    {
        return $user->can('force_delete_arsip_digital');
    }
}
