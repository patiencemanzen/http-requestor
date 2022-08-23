<?php

    namespace Patienceman\HttpRequestor;

    use Illuminate\Support\ServiceProvider;

    class HttpRequestorServiceProvider extends ServiceProvider {
        /**
         * Register services.
         *
         * @return void
         */
        public function register() {

        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot() {
            if ($this->app->runningInConsole()) {

                $this->publishes([
                  __DIR__.'HttpConfigs.php' => config_path('HttpConfigs.php'),
                ], 'HttpConfigs');

            }
        }
    }
