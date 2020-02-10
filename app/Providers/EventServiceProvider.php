<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Providers;

use App\Events\PostWasVisited;
use App\Events\UserWasLogged;
use App\Listeners\UpdateThePostCounter;
use App\Listeners\UpdateUserLastLogin;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
		],
		
		UserWasLogged::class => [
			UpdateUserLastLogin::class,
        ],
		
		PostWasVisited::class => [
			UpdateThePostCounter::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        //
    }
}
