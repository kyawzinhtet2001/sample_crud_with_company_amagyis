<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Interfaces\ClassroomRepositoryInterface::class,\App\Repositories\ClassroomRepository::class);
        $this->app->bind(\App\Interfaces\StudentRepositoryInterface::class,\App\Repositories\StudentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
