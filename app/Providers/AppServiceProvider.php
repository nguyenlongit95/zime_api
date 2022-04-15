<?php

namespace App\Providers;

use App\Repositories\Files\FileEloquentRepository;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Package\PackageEloquentRepository;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Users\UserEloquentRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class
        );
        $this->app->bind(
            PackageRepositoryInterface::class,
            PackageEloquentRepository::class,
        );
        $this->app->bind(
            FileRepositoryInterface::class,
            FileEloquentRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
