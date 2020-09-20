<?php

namespace Eav;

use Illuminate\Support\ServiceProvider;
use Eav\EntityObserve;
use Eav\EavModel;
use Eav\GlobalScope;

class EavServiceProvider extends ServiceProvider
{
    /**
     * Command mappings for the application
     *
     * @var array
     */
    protected $commands = [
        'Eav\Commands\MakeEntity',
        'Eav\Commands\MakeEntityModel',
        'Eav\Commands\MakeEntityMigration',
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
