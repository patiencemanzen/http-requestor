<?php
    namespace Patienceman\HttpRequestor;

    use Illuminate\Support\Arr;
    use Patienceman\HttpRequestor\Traits\HttpUtils;

    class HttpResponse {

        use HttpUtils;

        /**
         * Custom parsed response parameters
         */
        protected $response = [];

        /**
         * initialize HttpResponse instances
         *
         * @param array $response
         */
        public function __construct(array $response) {
            $this->response = $response;
        }

        /**
         * When you attempt to create single a property
         */
        public function __set($name, $value) {
            $this->response[$name] = $value;
        }

        /**
         * When you attempt to access single a property
         */
        public function __get($name) {
            return $this->response[$name];
        }

        /**
         * Extract all the parsed parameters
         *
         * @return array
         */
        public function extract() {
            return $this->response;
        }

        /**
         * create and sanitize response parameters
         *
         * @param array $response
         */
        public static function create(array $response) {
            $response =  new static($response);

            $response->sanitizeAndValidate();

            return $response;
        }

        /**
         * Sanitize and destroy all empty keys
         * from parsed inputs
         *
         * @return void
         */
        public function sanitizeAndValidate() {
            foreach ($this->response as $property => $value) {
                $this->when(($value == null), true, function () use ($property) {
                    $this->response = Arr::except($this->response, [$property]);
                });
            }
        }
    }
