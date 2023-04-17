<?php

namespace Support\AutoRegisterDomains;

use Illuminate\Events\Dispatcher;

class CacheClearSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle($event): void
    {
        $files = [
            'bootstrap/cache/domainAdminResourcesCache.php',
            'bootstrap/cache/domainServiceProviderCache.php',
            'bootstrap/cache/domainCommandsCache.php',
        ];

        foreach ($files as $file) {
            try {
                unlink(base_path($file));
            } catch (\Exception $exception) {
                dump($exception->getMessage());
            }
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen('cache:clearing', [$this, 'handle']);
    }
}
