<?php

namespace Neondigital\LaravelViewPresenter;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Config;

class ViewPresenterServiceProvider extends ServiceProvider
{
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
                $this->getConfigPath() => config_path('viewpresenter.php'),
            ], 'config');
        }

        $events->listen('composing:*', function ($view) {

            // Find View Presenter
            $presenterNamespace = Config::get('viewpresenter.namespace', 'App\\Http\\ViewPresenters\\');

            // Translate 'account.profile.view_picture' to 'Account\\Profile\\ViewPicture'
            $viewPaths = explode('.', $view->name());
            $viewPaths = array_map("camel_case", $viewPaths);
            $viewPaths = array_map("ucwords", $viewPaths);

            // Create fully qualified class name
            $viewPresenterClass = $presenterNamespace . implode('\\', $viewPaths);

            if (class_exists($viewPresenterClass)) {
                // Instantiate class and set data
                $viewPresenter = new $viewPresenterClass;
                $viewPresenter->setData($view->getData());
                
                // Remove existing view data
                foreach ($view->getData() as $key => $value) {
                    unset($view->{$key});
                }

                // Decorate and bind data to view
                $view->with($viewPresenter->decorate()->getData());
            }

        });
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
