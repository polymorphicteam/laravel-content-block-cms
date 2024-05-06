<?php

namespace Cswiley\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/cms.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cms::class, function () {
            return new Cms();
        });

        // Controller
        foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
            require_once $filename;
        }
        $this->app->make('Cswiley\Cms\CmsController');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cms');
        $this->registerPublish();
    }

    private function registerPublish()
    {
        $publishablePath = dirname(__DIR__) . '/publishable';
        $resourcePath    = dirname(__DIR__) . "/resources/";

        $publishable = [
            'public' => [
                "{$publishablePath}/assets" => public_path(config('cms.assets_path'))
            ],
            'config' => [
                "{$publishablePath}/config/cms.php" => config_path('cms.php'),
            ],
            'views'  => [
                "{$resourcePath}/views" => resource_path('views/vendor/cms'),
            ]
        ];

        foreach ($publishable as $label => $val) {
            $this->publishes($val, $label);
        }
    }
}
