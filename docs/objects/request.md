Overview
--------

The Request object encapsulates data sent during a HTTP request. The `Tonis\Http\Request` object decorates 
`Zend\Stratigility\Http\Request` which implements `Psr\Http\Message\RequestInterface`. It's highly recommended that you
review [PSR-7 - HTTP messages interfaces](http://www.php-fig.org/psr/psr-7/) to fully understand PSR-7.

The following are methods available in addition to what PSR-7 provides.

ArrayAccess
-----------

`Tonis\Http\Request` implements `ArrayAccess` to provide access to the route match parameters.

```php
$app->get('/hello/{name}', function ($request, $response) {
    // output is Hello, foo
    return $response->write('Hello, ' . $request['name']);
});
```

app()
-----

`public function app(): Tonis\App`

Retrieve the instance of `Tonis\App` bound to the request.

```php
$app->get('/', function ($request, $response) {
    // output is Tonis\App
    return $response->write(get_class($request->app()); 
});
```

getParams()
-----------

`public function getParams(): array`

Get all route params from the matched route.

```php
$app->get('/hello/{name}', function ($request, $response) {
    // output is array ('name' => 'foo',)
    return $response->write(var_export($request->getParams(), true));
});
```
