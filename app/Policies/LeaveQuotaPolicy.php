<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveQuota;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveQuotaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_leave::quota');
    }

    public function view(User $user, LeaveQuota $leaveQuota): bool
    {
        return $user->can('view_leave::quota');
    }

    public function create(User $user): bool
    {
        return $user->can('create_leave::quota');
    }

    public function update(User $user, LeaveQuota $leaveQuota): bool
    {
        return $user->can('update_leave::quota');
    }

    public function delete(User $user, LeaveQuota $leaveQuota): bool
    {
        return $user->can('delete_leave::quota');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_leave::quota');
    }
}
