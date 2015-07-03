Quick Start
-----------

Here is an example of a very basic Tonis app. This will look familiar to many micro-frameworks.

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Tonis\App;
$app->get('/', function($request, $response) {
    $response->write('Hello from Tonis');
});

$server = Zend\Diactoros\Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$server->listen();
```

You can run it with:

```php -S 127.0.0.1:8080 index.php```

Then, load [http://127.0.0.1:8080](http://127.0.0.1:8080) in a browser to see the output.

Router Middleware
-----------------

This example shows how to mount middleware using a router instance. This is commonly used to reuse chunks of code.

```php
$app = new \Tonis\App;

// this will be executed for every request
$app->add(function ($request, $response, $next) {
    $response->write('pre' . PHP_EOL);
    $response = $next($request, $response);
    $response->write('post' . PHP_EOL);
    
    return $response
});

$app->get('/', function ($request, $response) {
    return $response->write('GET on /');
});

// routers are middleware and may be mounted to the app
// additionally, they are reusable, and you can use them to create bundles/packages/modules
$router = $app->router();
$router->get('/articles', function ($request, $response) {
    return $response->write('GET on /articles');
});

// mount the router back to the application
$app->add($router);
```

A `GET` request to `/` using the above app would return:

```
pre
GET on /
post
```

A `GET` request to `/articles` using the above app would return:
 
```
pre
GET on /articles
post
```

Tonis Project
-------------

For a more detailed example you can clone the [Tonis Project](http://github.com/tonis-io/tonis-project) which 
includes a recommended application skeleton.

Official Middleware
-------------------

Check out the [official middleware](/resources/official-middleware) for some examples of middleware.
