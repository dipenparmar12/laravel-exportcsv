<?php

namespace Dipenparmar12\ExportCsv;

use Dipenparmar12\ExportCsv\Commands\ExportCsvCommand;
use Illuminate\Support\ServiceProvider;

class ExportCsvServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Register the service the package provides.
        $this->app->singleton('exportcsv', function ($app) {
            return new ExportCsv;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['exportcsv'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Registering package commands.
        $this->commands([
            ExportCsvCommand::class
        ]);
    }
}
