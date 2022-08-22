<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Http Methods
    |--------------------------------------------------------------------------
    | these are predifined http method.
    | but you may add your custom methods as you want,
    | and you're not allowed to change the default one
    | because are in use
    |
    */
    'methods' => [
        /**
         * Used to create subordinate resources.
         */
        'POST' => 'POST',

        /**
         * Used to delete a resource identified by filters or ID.
         */
        "DELETE" => "DELETE",

        /**
         * Can be Used to modify resources.
         */
        "UPDATE" => "UPDATE",

        /**
         * Read (or retrieve) a representation of a resource.
         */
        "GET" => "GET"
    ],

    /*
    |--------------------------------------------------------------------------
    | Http Status
    |--------------------------------------------------------------------------
    | these are predifined http status.
    | but you may add your custom methods as you want,
    | and you're not allowed to change the default one
    | because are in use
    |
    */
    'status' => [
        /**
         *  When the operation goes well
         */
        'OK' => 200,

        /**
         * where given values created
         */
        'CREATED' => 201,

        /**
         * Request has been accepted for processing,
         * but the processing has not been completed
         */
        'ACCEPTED' => 202,

        /**
         * The operation changed the processing states
         */
        'REDIRECT' => 302,

        /**
         * The server can not process the request due to
         * an apparent client error
         */
        'BAD_REQUEST' => 400,

        /**
         * use when authentication is required and has failed
         * or has not yet been provided
         */
        'UNAUTHORIZED' => 401,

        /**
         * The requested resource could not be found
         */
        'NOT_FOUND' => 404,

        /**
         * when an unexpected condition was encountered
         * and no more specific message is suitable.
         */
        'SERVER_ERROR' => 500
    ],
];
