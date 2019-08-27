<?php

namespace Hackathon\Application\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Hackathon\Application\Events\ExampleEvent' => [
            'Hackathon\Application\Listeners\ExampleListener',
        ],
    ];
}
