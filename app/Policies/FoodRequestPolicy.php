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
        return $user->isCustomer() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isCustomer() && $foodRequest->customer_id === $user->id
            || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isCustomer() && $foodRequest->customer_id === $user->id
            && in_array($foodRequest->status, ['pending', 'payment_pending']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isCustomer() && $foodRequest->customer_id === $user->id
            && in_array($foodRequest->status, ['pending', 'payment_pending']);
    }

    /**
     * Determine whether the user can ship the order (supplier).
     */
    public function ship(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isSupplier() && $foodRequest->status === 'paid';
    }

    /**
     * Determine whether the user can mark as delivered (supplier).
     */
    public function markDelivered(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isSupplier() && $foodRequest->status === 'shipping';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, FoodRequest $foodRequest): bool
    {
        return $user->isSuperAdmin() && in_array($foodRequest->status, ['pending', 'payment_pending', 'paid']);
    }
}
