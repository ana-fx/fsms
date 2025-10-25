<?php

namespace App\Policies;

use App\Models\FoodRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isFoundation() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isFoundation() && $foodRequest->foundation_id === $user->id
            || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isFoundation();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isFoundation() && $foodRequest->foundation_id === $user->id
            && $foodRequest->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isFoundation() && $foodRequest->foundation_id === $user->id
            && $foodRequest->status === 'pending';
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isSuperAdmin() && $foodRequest->status === 'pending';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isSuperAdmin() && in_array($foodRequest->status, ['pending', 'approved']);
    }
}
