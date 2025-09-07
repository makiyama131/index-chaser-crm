<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\StatusHistory;
use Illuminate\Support\Facades\Auth;
class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        // When a new customer is created, log their initial status
        StatusHistory::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id() ?? $customer->user_id,
            'from_status_id' => null, // No previous status
            'to_status_id' => $customer->status_id,
        ]);
    }

    /**
     * Handle the Customer "updating" event.
     */
    public function updating(Customer $customer): void
    {
        // Check if the status_id is actually being changed
        if ($customer->isDirty('status_id')) {
            StatusHistory::create([
                'customer_id' => $customer->id,
                'user_id' => Auth::id() ?? $customer->user_id,
                'from_status_id' => $customer->getOriginal('status_id'),
                'to_status_id' => $customer->status_id,
            ]);
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
