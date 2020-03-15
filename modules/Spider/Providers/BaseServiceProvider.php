<?php

namespace Spider\Providers;

use Illuminate\Support\ServiceProvider;
use Spider\Console\Commands\Worker;
use Spider\Console\Commands\Task;
use Spider\Console\Commands\Collector;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Worker::class,
                Task::class,
                Collector::class
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
