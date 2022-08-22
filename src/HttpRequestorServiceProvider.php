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
            $this->publishes([
                __DIR__.'/../config/http-requestor.php' => config_path('configs/HttpConcerns.php'),
            ]);
        }
    }
