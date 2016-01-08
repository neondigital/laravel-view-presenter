<?php

namespace Neondigital\LaravelViewPresenter;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Config;

class ViewPresenterServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'composing:*' => [
            'Neondigital\LaravelViewPresenter\Listeners\Composing',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        if (!$this->isLumen()) {
            $this->publishes([
                $this->getConfigPath() => $this->app->make('path.config') . '/viewpresenter.php',
            ], 'config');
        }
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/viewpresenter.php';
    }

    /**
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
