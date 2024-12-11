<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_leave::request');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->can('view_leave::request');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_leave::request');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->status !== 'pending') {
            return false;
        }
        
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->id === $leaveRequest->user_id && $user->can('update_leave::request');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($leaveRequest->status !== 'pending') {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->id === $leaveRequest->user_id && $user->can('delete_leave::request');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('delete_any_leave::request');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('force_delete_leave::request');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('force_delete_any_leave::request');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('restore_leave::request');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('restore_any_leave::request');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('replicate_leave::request');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->hasRole('super_admin') && $user->can('reorder_leave::request');
    }

    /**
     * Determine whether the user can approve leave request.
     */
    public function approve(User $user): bool
    {
        return $user->can('approve_leave::request');
    }

    /**
     * Determine whether the user can reject leave request.
     */
    public function reject(User $user): bool
    {
        return $user->can('reject_leave::request');
    }
}
