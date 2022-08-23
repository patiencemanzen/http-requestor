<?php
    namespace Patienceman\HttpRequestor;

    use Closure;
    use Illuminate\Http\JsonResponse;
    use Patienceman\HttpRequestor\HttpResponse;
    use Throwable;

    trait HttpUtils {
        /**
         * return error message when the passed statement
         * is match the specified condition
         *
         * @param $statement
         * @param bool $condition
         * @param callable|null $callable
         * @return callable|mixed $callbale
         */
        public function when($statement, $condition = true, $callable = null) {
            $state = ($condition) ? $statement : !$statement;

            if ($state)
                return (is_callable($callable))
                        ? $callable()
                        : (!$state);
        }

        /**
         * Catch a potential exception and return a default value.
         *
         * @param  callable  $callback
         * @param  mixed  $rescue
         * @param  bool  $report
         * @return mixed
         */
        function catchIf(callable $callback, $execute = null, $report = true) {
            try {
                return $callback();
            } catch (Throwable $e) {
                if($report) report($e);

                return $execute instanceof Closure ? $execute($e) : $execute;
            }
        }

        /**
         * fire an exception in response format
         *
         * @param string $errorMessage
         * @param string|int $status
         */
        function triggerException($errorMessage, $status = 400) {
            throw new HttpRequestorException($errorMessage, $status);
        }

        /**
         * Handle the success response for Ajax requests
         *
         * @param mixed $data The data to be passed to the response
         * @param string|null $message The message to be passed to the response
         * @param int $code The response status code
         * @return JsonResponse
         */
        public function httpJsonResponse(?string $message, $data = null, array $with = [], int $code = 200): JsonResponse {
            $response = HttpResponse::create(array_merge([
                'success' => ($code > 199 && $code < 300) ? true : false,
                'data' => $data,
                'message' => $message,
            ], $with));

            return response()->json($response->extract(), $code);
        }

        /**
         * Get http stardands methods or status from config file
         *
         * @param string $stads
         */
        public function getHttpStads($stads) {
            if($stads == "methods")
                return config('http-requestor.methods');

            if($stads == "status")
                return config('http-requestor.status');
        }

        /**
         * Check if http-requestor config file published
         *
         * @param mixed|\Illuminate\Config\Repository
         * @return Exception|
         */
        public function exceptionalConfig($config) {
            if(file_exists(config_path('http-requestor.php')))
                throw new \Exception('Missing config file, Please publich http-requestor config file');

            return $config;
        }
    }
