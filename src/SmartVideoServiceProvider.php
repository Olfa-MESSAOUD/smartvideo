<?php
namespace content\smartvideo;
use Illuminate\Support\ServiceProvider;

class SmartVideoServiceProvider extends ServiceProvider
{
    function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
    
    public function register()
    {
        $this->app->make('content\smartvideo\Controllers\addController');
        $this->app->make('content\smartvideo\Controllers\annotateController');
        $this->app->make('content\smartvideo\Controllers\completeController');
        $this->app->make('content\smartvideo\Controllers\connectController');
        $this->app->make('content\smartvideo\Controllers\deleteController');
        $this->app->make('content\smartvideo\Controllers\likeController');
        $this->app->make('content\smartvideo\Controllers\pauseController');
        $this->app->make('content\smartvideo\Controllers\playController');
        $this->app->make('content\smartvideo\Controllers\rateController');
        $this->app->make('content\smartvideo\Controllers\searchController');
        $this->app->make('content\smartvideo\Controllers\seekController');
        $this->app->make('content\smartvideo\Controllers\selectController');
        $this->app->make('content\smartvideo\Controllers\shareController');
        $this->app->make('content\smartvideo\Controllers\terminateController');
    }
    
}

   