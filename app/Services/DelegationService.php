<?php

namespace App\Services;

use App\Models\UserDelegation;
use App\Models\User;

class DelegationService
{
    /**
     * Check if user is delegated to sign for another user
     */
    public static function checkDelegation(int $targetUserId, int $actingUserId): bool
    {
        return UserDelegation::where('user_id', $targetUserId)
            ->where('delegate_user_id', $actingUserId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
    }
}
