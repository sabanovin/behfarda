<?php

namespace SabaNovin\Behfarda;

use Illuminate\Support\ServiceProvider;

class BehfardaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/behfarda.php', 'behfarda'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/behfarda.php' => config_path('behfarda.php'),
        ]);
    }
}
