<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can approve/reject special price under floor price.
     * Hanya Manager Sales atau Admin yang berhak memberikan persetujuan harga khusus.
     */
    public function approveSpecialPrice(User $user, Quotation $quotation): bool
    {
        return $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales');
    }

    /**
     * Determine whether the user can manage negotiations.
     */
    public function negotiate(User $user, Quotation $quotation): bool
    {
        if ($user->isCustomer()) {
            return $quotation->customer_id === $user->id;
        }

        return $user->isAdmin() || $user->divisi === 'sales';
    }

    /**
     * Determine whether the user can override negotiation quota.
     */
    public function overrideQuota(User $user, Quotation $quotation): bool
    {
        return $user->isAdmin() || ($user->isManager() && $user->divisi === 'sales');
    }
}
