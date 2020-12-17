<?php

namespace Ziczac\EavLaravel;

use Illuminate\Support\ServiceProvider;
use Ziczac\EavLaravel\EntityObserve;
use Ziczac\EavLaravel\EavModel;
use Ziczac\EavLaravel\GlobalScope;

class EavServiceProvider extends ServiceProvider
{
    /**
     * Command mappings for the application
     *
     * @var array
     */
    protected $commands = [
        'Ziczac\EavLaravel\Commands\MakeEntity',
        'Ziczac\EavLaravel\Commands\MakeEntityModel',
        'Ziczac\EavLaravel\Commands\MakeEntityMigration',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GlobalScope::class, function ($app) {
            return new GlobalScope();
        });
        $this->app->singleton(EntityObserve::class, function ($app) {
            return new EntityObserve(new EavModel(), $app['events']);
        });

        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
