<?php
    namespace Patienceman\HttpRequestor\Facades;

    use Illuminate\Support\Facades\Facade;

    class HttpRequestor extends Facade {
        protected static function getFacadeAccessor(){
            return 'HttpRequestor';
        }
    }
