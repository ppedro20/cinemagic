<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Screening;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ScreeningPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->type === 'A';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Screening $screening): bool
    {
        if ($user?->type === 'A') {
            return true;
        }

        $currentDate = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->subMinutes(5)->format('H:i');
        $endDate = Carbon::now()->addWeeks(2)->toDateString();

        return $screening->date > $currentDate && $screening->date < $endDate ||
                ($screening->date === $currentDate && $screening->start_time >= $currentTime);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->type === 'A';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Screening $screening): bool
    {
        return $user->type === 'A';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Screening $screening): bool
    {
        return $user->type === 'A';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Screening $screening): bool
    {
        return $user->type === 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Screening $screening): bool
    {
        return $user->type === 'A';
    }
}
