<?php
namespace Hammal;
use Illuminate\Support\ServiceProvider;
class HammalServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Hammal\Commands\MakeDataModel',
    ];
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        $this->commands($this->commands);
    }

    public function boot()
    {
    	$this->publishes([
            __DIR__.'/Publish/Articles.php' => app_path('Http/DataModels/Articles.php')
        ], 'dataModels');
        $this->publishes([
            __DIR__.'/Publish/Article.php' => app_path('Http/Models/Article.php')
        ], 'models');
        $this->publishes([
            __DIR__.'/Publish/migrations' => database_path('migrations')
        ], 'migrations');
    }
}
