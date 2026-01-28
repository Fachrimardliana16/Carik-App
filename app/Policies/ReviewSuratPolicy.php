<?php

namespace App\Policies;

use App\Models\ReviewSurat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewSuratPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_review_surat');
    }

    public function view(User $user, ReviewSurat $reviewSurat): bool
    {
        return $user->can('view_review_surat');
    }

    public function create(User $user): bool
    {
        return $user->can('create_review_surat');
    }

    public function update(User $user, ReviewSurat $reviewSurat): bool
    {
        return $user->can('update_review_surat');
    }

    public function delete(User $user, ReviewSurat $reviewSurat): bool
    {
        return $user->can('delete_review_surat');
    }

    public function restore(User $user, ReviewSurat $reviewSurat): bool
    {
        return $user->can('restore_review_surat');
    }

    public function forceDelete(User $user, ReviewSurat $reviewSurat): bool
    {
        return $user->can('force_delete_review_surat');
    }
}
