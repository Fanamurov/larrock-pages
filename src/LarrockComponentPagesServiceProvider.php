<?php

namespace Larrock\ComponentPages;

use Illuminate\Support\ServiceProvider;

class LarrockComponentPagesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'larrock');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/larrock')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make(PageComponent::class);

        if ( !class_exists('CreatePageTable')){
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/database/migrations/0000_00_00_000000_create_page_table.php' => database_path('migrations/'.$timestamp.'_create_page_table.php')
            ], 'migrations');
        }
    }
}
