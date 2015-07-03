Overview
--------

Middleware is a [callable](http://php.net/manual/en/language.types.callable.php) that interacts with the request-response cycle. 
It may modify the request/response, terminate the request-response cycle early, and call the next middleware in the stack. 
Middleware accepts the following arguments:

  * ```$request``` **MUST** implement `Psr\HttpMessage\RequestInterface`
  * ```$response``` **MUST** implement \Psr\HttpMessage\ResponseInterface`
  * ```$next``` **MUST** be a callable
   
Middleware may be loaded at the application level or router level and may include a mount path. Loading a series of 
middleware on the same mount path will create a stack for that path. 

Application Level Middleware
----------------------------

Application level middleware is added to `Tonis` with the `add` method and may include a path to mount to.

```php
$app = new \Tonis\App;

// middleware gets executed on every request 
$app->add(function ($request, $response, $next) {
    $response->write('always executed');
    return $next($request, $response);
});

$app->get('/articles/{id}', function ($request, $response) {
    $response->end('Articles');
});
``` 

Router Level Middleware
-----------------------

Router level middleware is similar to application middleware except it is added to an instance of `Tonis\Router`.
Typically, you'll create a router instance using the `router()` method.

```php
$app    = new \Tonis\App;
$router = $app->router():

$router->add(function ($request, $response, $next) {
    $response->write('always executed');
    return $next($request, $response);
});

$router->get('/{id}', function ($request, $response) {
    $response->end('Articles');
});

// this mounts the $router middleware
$app->add($router);
```
**NOTE** Middleware added to a router and then mounted to Tonis with `add` will only be executed if the path matches.
