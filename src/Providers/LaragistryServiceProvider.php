<?php

namespace Spaaleks\Laragistry\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class LaragistryServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom($this->getPackageBasePath().'/database/migrations');
        $this->mergeConfigFrom($this->getPackageBasePath().'/config/laragistry.php', 'laragistry');
    }

    /**
     * Get the package base path.
     *
     * @return string
     */
    public function getPackageBasePath()
    {
        return str_replace('src/Providers', '', __DIR__);
    }

}
