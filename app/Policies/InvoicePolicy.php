<?php

namespace App\Policies;

use App\Ad;
use App\Bid;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewInvoice(User $user, Ad $ad, Bid $wonBid)
    {
        return $wonBid->user_id == $user->id || $user->is_admin();
    }
}
