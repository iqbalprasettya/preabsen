<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Holiday;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_holiday');
    }

    public function view(User $user, Holiday $holiday): bool
    {
        return $user->can('view_holiday');
    }

    public function create(User $user): bool
    {
        return $user->can('create_holiday');
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return $user->can('update_holiday');
    }

    public function delete(User $user, Holiday $holiday): bool
    {
        return $user->can('delete_holiday');
    }

    public function restore(User $user, Holiday $holiday): bool
    {
        return $user->can('restore_holiday');
    }

    public function forceDelete(User $user, Holiday $holiday): bool
    {
        return $user->can('force_delete_holiday');
    }

    public function toggleActive(User $user, Holiday $holiday): bool
    {
        return $user->can('toggle_active_holiday');
    }
}
