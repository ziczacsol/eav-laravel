<?php

namespace Eav;

use Illuminate\Support\ServiceProvider;
// use App\Eav\EntityObserve;
// use App\Eav\EavModel;
// use App\Eav\GlobalScope;

class EavServiceProvider extends ServiceProvider
{
    /**
     * Command mappings for the application
     *
     * @var array
     */
    protected $commands = [
        'Commands\MakeEntity',
        'Commands\MakeEntityModel',
        'Commands\MakeEntityMigration',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(GlobalScope::class, function ($app) {
        //     return new GlobalScope(new EavModel());
        // });
        // $this->app->singleton(EntityObserve::class, function ($app) {
        //     return new EntityObserve(new EavModel(), $app['events']);
        // });

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
