<?php

    namespace Patienceman\HttpRequestor\Exceptions;

    use Exception;
    use Patienceman\HttpRequestor\Configs\HttpStatuses;
    use Patienceman\HttpRequestor\Traits\HttpUtils;

    class HttpRequestorException extends Exception {

        use HttpUtils;

        /**
         * will contain additional data in the response
         * @var Array
         */
        protected $data = [];

        /**
         * Default status code
         *
         * @var int
         */
        protected $status = 400;

        protected $guard;

        /**
         * Initialize HttpRequestorException instances
         *
         * @param string $message
         * @param string|int|mixed|null $status
         * @param string $guard
         */
        public function __construct($message, $status = null, $guard = 'api') {
            parent::__construct($message);

            $this->status = !$status ? $this->status : $status;

            $this->guard = $guard;
        }

        /**
         * Render an exception into an HTTP response.
         *
         * @return \Illuminate\Http\Response
         */
        public function render() {
            if ($this->guard == 'web')
                return $this->renderWebException();

            return $this->renderJsonResponse();
        }

        /**
         * Data that will be appended to the response
         *
         * @return HttpRequestorException
         */
        public function withData($data):HttpRequestorException {
            $this->data = $data;

            return $this;
        }

        /**
         * Set a status
         *
         * @param string $status
         * @return HttpRequestorException
         */
        public function withStatus($status):HttpRequestorException {
            $this->status = $status;

            return $this;
        }

        /**
         * Response to return for this exception
         */
        public function renderJsonResponse() {
            if(!request()->expectsJson())
                return redirect()->back()->withInput();

            return $this->buildResponse();
        }

        /**
         * arrange the response message
         */
        public function buildResponse() {
            if ($this->data == []){
                $this->httpJsonResponse($this->getMessage(), null, [
                    'customer_care' => 'For more explanation about this, Please call the center'
                ], 400);
            }

            return $this;
        }

        /**
         * return a customer page for error displaying page
         */
        public function renderWebException() {
            return view('errors.custom_error')
                    ->with('message', $this->getMessage());
        }
    }
