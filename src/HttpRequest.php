<?php
    namespace Patienceman\HttpRequestor;

    use Exception;
    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Http;
    use Patienceman\HttpRequestor\Traits\ConstantReader;
    use Patienceman\HttpRequestor\Traits\HttpUtils;

    class HttpRequest {

        use HttpUtils, ConstantReader;

        /**
         * configure common request paths.
         */
        protected $client;

        /**
         * Meta-data associated with the API request and response.
         */
        protected $headers;

        /**
         * Configure the base server url to external application
         */
        protected $server;

        /**
         * oAuth server config headers
         */
        protected $oAuthServer = [];

        /**
         * Data that is sent in the
         * HTTP Message Body (if any)
         */
        protected $payload = [];

        /**
         * Get the class responses
         */
        public $response;

        /**
         * Get the class responses
         */
        public $error;

        /**
         * All stardand request methods
         */
        protected $stdMethods;

        /**
         * All stardand request methods
         */
        protected $stdStatus;

        /**
         * Create a new HttpRequest instance.
         *
         * @param string|null $server
         * @return HttpRequest
         */
        public function __construct(string $server = null) {
            $this->client = new Client();

            $this->server = $server;

            $this->stdMethods = config('http-requestor.methods');

            $this->stdStatus = config('http-requestor.status');
        }

        /**
         * Create new server
         *
         * @param string|null $server
         */
        public static function server(string $server = null) {
            $server = new static($server);

            return $server;
        }

        /**
         * Set Oauth server parameters
         *
         * @param array $data;
         * @return HttpRequest
         */
        public function buildHttpQuery(array $data) {
            $this->oAuthServer = $data;

            return $this;
        }

        /**
         * Set headers to be used in any clients requests
         *
         * @param array|null $header
         * @return HttpRequests
         */
        public function withHeaders(array $headers = null){
            $this->headers = $headers;

            return $this;
        }

        /**
         * Request access token from specified Oauth server
         *
         * @param string $url
         * @return String|Throwable
         */
        public function requestToken(string $url = null) {
            return $this->catchIf(function() use ($url) {
                $response = Http::asForm()->post($this->server . $url, $this->oAuthServer);

                return $response->json()['access_token'];
            }, function ($e) {
                return $this->triggerException($e);
            });
        }

        /**
         * Parse a payload to server request
         *
         * @param array|null $payload
         * @param bool $json
         * @return HttpRequests
         */
        public function withData(array $payload = [], bool $json = true) {
            $this->payload = $payload;

            $this->when($json, true, function() use ($payload) {
                $this->payload = json_encode($payload);
            });

            return $this;
        }

        /**
         * Return the current request response
         *
         * @param string $method
         * @return mixed|string
         */
        protected function prepare($method) {
            $this->when(in_array($method, $this->stdMethods, TRUE), false, function() use ($method) {
                throw new Exception("Undefined request method - {$method}");
            });

            return strtolower(trim($method));
        }

        /**
         * Make request to specified server with method
         *
         * @param string $url
         * @param string $method
         * @return HttpRequests
         */
        public function request(string $method, string $url) {
            $exception = function($e) {
                return $this->triggerException($e);
            };

            $this->catchIf(function() use ($method, $url) {
                $method = $this->prepare($method);

                $response = Http::withHeaders($this->headers)
                                ->$method($this->server . $url, $this->payload);

                $this->response = $response->json();
            }, $exception);

            return $this;
        }

        /**
         * HttpRequest accessor
         *
         * @return HttpRequest
         */
        public function resources() {
            return array_merge($this->stdMethods, $this->stdStatus);
        }

        /**
         * Return the current request response
         *
         * @param Callable $callable
         * @return Callable|mixed
         */
        public function then($callable) {
            return (is_callable($callable))
                    ? $callable($this->response, $this->error)
                    : $callable;
        }
    }
