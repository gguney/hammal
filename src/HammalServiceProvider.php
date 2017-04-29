<?php
namespace GGuney\Hammal;

use Illuminate\Support\ServiceProvider;

class HammalServiceProvider extends ServiceProvider
{
    protected $commands = [
        'GGuney\Hammal\Commands\MakeDataModel',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->mergeConfigFrom(__DIR__ . '/Publish/config/hammal.php', 'hammal');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Publish/config/hammal.php' => config_path('hammal.php'),
        ]);
    }

}
