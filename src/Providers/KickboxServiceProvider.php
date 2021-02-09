<?php


namespace Juanparati\LaravelKickbox\Providers;


use Illuminate\Support\ServiceProvider;
use Juanparati\LaravelKickbox\Kickbox;


/**
 * Class KickboxServiceProvider.
 *
 * @package Providers
 */
class KickboxServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap service.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/kickbox.php' => config_path('kickbox.php'),
        ]);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/kickbox.php', 'kickbox');

        $this->app->singleton(Kickbox::class, function () {
            return new Kickbox(config('kickbox'));
        });
    }

}