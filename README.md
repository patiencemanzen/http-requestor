# HttpRequestor from Patienceman

HTTP Requestor: Package for a client request that supports you to make an external service request easily and with fast usage.

## Installation

Installing the package doesn't require much requirement except to paste the following command in the laravel terminal, and you're good to go.

```bash
  composer require patienceman/httprequestor
```

## Usage

To use HTTP Requestor u need to call  ```HttpRequest ``` class,
But before everything you need to publish the config file for "HTTP requestor",
Just run!
```bash 
php artisan vendor:publish --tag=HttpConfigs
```
Let's take the example with it and see the simplicity

```PHP
  $http = HttpRequest::server('https://commandement.com');
```
So!, now you can make requests with all standard methods.
There are two ways this package is useful, by requesting 
an access token to the server or by sending a normal request

let's take the example of requesting access token
```PHP
  $http = HttpRequest::server('https://commandement.com');

  $http->buildHttpQuery([
      'grant_type' => 'client_credentials',
      'client_id' => config('services.custom.client_id'),
      'client_secret' => config('services.custom.client_secret'),
      'scope' => config('services.custom.scope)
  ]);

  return $http->requestToken('/oauth/token');
```
So now we are able to request an access tokens.

Let also take the example of a normally api request with standard HTTP methods
```PHP
  $http = HttpRequest::server('https://commandement.com');

  $http->withHeaders([
      'Authorization' => $accessToken,
      'Accept' => 'application/json',
  ]);
  
  $http->withData([
    'name' => 'moses',
    'location' => 'mountain-sinai',
    'command' => 'navigate islaels'
  ]);
  
  $http->request('POST', '/api/sync-movement')

  return $http->then(function($response, $error) {
      return $response->last_update_at;
  });
```
Fantastic right! and super cool!

So now on Moses are able to make a super request to our commandment server!,

This package also contains the HttpResponse Helper class, which support your JSON response to remove all null value, on the go!
To use it, u need just one class ```bash HttpResponse ```

let's take an example with it and see how it works!
```PHP 
  function httpJsonResponse(?string $message, $data = null, array $with, int $code = 200): JsonResponse {
      $response = HttpResponse::create(array_merge([
          'success' => ($code > 199 && $code < 300) ? true : false,
          'data' => $data,
          'message' => $message
      ], $with));

      return response()->json($response->extract(), $code);
  }
```
And whenever one of the passed parameters is null, the HttpResponse deletes it from the parameters,
soon or later we're going to another way of making it optional.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
