Overview
--------

The `Tonis\Router` handles all routing in Tonis and is built on `nikic/FastRoute` for optimal performance. For more
information on routing see the [routing documentation](/basics/routing).

The following are methods available.

HTTP Verbs
----------

`public function verb(string $path, callable $handler): \Tonis\Router\Route`

Creates a route that matches on the specified HTTP verb and the path specified. 

HTTP Verb can be one of: `get` `post` `put` `patch` `delete` `head` `options`

```php
$router->get('/', function ($request, $response) { ... });
```

any()
-----

`public function any(string $path, callable $handler): \Tonis\Router\Route`

Creates a route that matches any HTTP verb and the path specified.

```php
$router->any('/', function ($request, $response) { ... });
```

param()
-------

`public function param(string $param, callable $handler): void`

Adds middleware for a matched route parameter. 

```php
// this will be called anytime "article_id" exists in a route
$router->param('article_id', function ($request, $response, $value) {
    // assume repository was injected
    $article = $this->repository->find($value);
    
    // this will be caught by the default error handler and show a nice 404 page
    if (!$article) {
        throw new \RuntimeException('No article', 404);
    }
    
    // now "article" is available to any handler with article_id in the route
    // this let's you easily reuse common code
    $request['article'] = $article;
    return $next($request, $response);
});

// create a route with "article_id" so the handler can be called
$router->get('/articles/{article_id}', function () {});
```

query()
-------

`public function query(string $param, callable $handler): void`

Adds middleware for a query parameter. 

```php
// this will be called anytime "article_id" exists as a query parameter
$router->query('article_id', function ($request, $response, $value) {
    // assume repository was injected
    $article = $this->repository->find($value);
    
    // this will be caught by the default error handler and show a nice 404 page
    if (!$article) {
        throw new \RuntimeException('No article', 404);
    }
    
    // now "article" is available to any handler with article_id in the route
    // this let's you easily reuse common code
    $request['article'] = $article;
    return $next($request, $response);
});

// create a route for the articles
$router->get('/articles', function () {});

// make a request with the query parameter
// GET: /articles?article_id=1234
```

add()
-----

`public function add(callable $middleware): void`

Add middleware to the router. Middleware is called prior to dispatching the route handler and only if a route matches.

```php
$router->add(function ($request, $response, $next) {
    $response->write('pre ');
    $response = $next($request, $response);
    $response->write(' post');
    
    return $response;
});

// a call to "GET /" would output "pre GET / post"
$router->get('/', function ($request, $response) {
    return $response->write('GET /');
});
```

group()
-----

`public function group(string $prefix, callable $handler): \Tonis\Router\GroupInterface`

Groups routes under a common prefix. Great for combining similar route paths such as API versions.

```php
$router->group('/api/v1', function (\Tonis\Router\Group $v1) {
    $v1->get('/articles', function () {});
    $v1->get('/articles/{id}', function () {});
});

// /api/v1/articles and /api/v1/articles/:id would be routable.
```
