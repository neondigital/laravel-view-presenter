<?php

namespace Neondigital\LaravelViewPresenter\Listeners;

use Illuminate\Contracts\Config\Repository as Config;

class Composing
{
    protected $config;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Handle the event.
     *
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle($view)
    {
        // Find View Presenter
        $presenterNamespace = $this->config->get('viewpresenter.namespace', 'App\\Http\\ViewPresenters\\');

        // Translate 'account.profile.view_picture' to 'Account\\Profile\\ViewPicture'
        $viewPaths = explode('.', $view->name());
        $viewPaths = array_map("camel_case", $viewPaths);
        $viewPaths = array_map("ucwords", $viewPaths);

        // Create fully qualified class name
        $viewPresenterClass = $presenterNamespace . implode('\\', $viewPaths);

        if (!class_exists($viewPresenterClass)) {
            $viewPresenterClass = \Neondigital\LaravelViewPresenter\viewPresenter::class;
        }

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
}
