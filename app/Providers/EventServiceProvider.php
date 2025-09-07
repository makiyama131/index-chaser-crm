<?php

namespace App\Providers;

// ▼▼▼ ADD THESE USE STATEMENTS ▼▼▼
use App\Models\Customer;
use App\Observers\CustomerObserver;
// ▲▲▲ END OF STATEMENTS ▲▲▲

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The model observers for your application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    // ▼▼▼ ADD YOUR OBSERVER HERE ▼▼▼
    protected $observers = [
        Customer::class => [CustomerObserver::class],
    ];
    // ▲▲▲ END OF OBSERVER ▲▲▲
    
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}