Overview
--------

The Response object encapsulates data sent during a HTTP response. The `Tonis\Http\Response` object decorates 
`Zend\Stratigility\Http\Response` which implements `Psr\Http\Message\ResponseInterface`. It's highly recommended that you
review [PSR-7 - HTTP messages interfaces](http://www.php-fig.org/psr/psr-7/) to fully understand PSR-7.

The following are methods available in addition to what PSR-7 provides.

app()
----------------

`public function app(): Tonis\App`

Retrieve the instance of `Tonis\App` bound to the response.

```php
$app->get('/', function ($request, $response) {
    // output is Tonis\App
    return $response->write(get_class($reponse->app()); 
});
```

json()
------

`public function json(mixed $input): Tonis\Http\Response`

Encodes the input as JSON and sets the Content-Type header to application/json.

```php
$app->get('/', function ($request, $response) {
    // output is {"foo": "bar"}
    return $response->json(['foo' => 'bar']);
});
```

jsonp()
-------

`public function jsonp(mixed $input, string $callback): Tonis\Http\Response`

Encodes the input as JSONP and sets the Content-Type header to application/javascript.

```php
$app->get('/', function ($request, $response) {
    // output is MyFunction({"foo": "bar"});
    return $response->jsonp(['foo' => 'bar'], 'MyFunction);
});
```

redirect()
----------

`public function redirect(string $url, bool $permanent = false): Tonis\Http\Response`

Returns a response with a proper status code and Location header. If permanent is true,
a 301 status code is used instead of 302.

```php
$app->get('/', function ($request, $response) {
    return $response->redirect('http://www.example.com');
});
```

redirectToRoute()
----------------

`public function redirect(string $route, array $params = [], bool $permanent = false): Tonis\Http\Response`

Redirects to a named route.

```php
$app
    ->get('/foo', function ($request, $response) {
        return $response->write('bar');
    })
    ->name('foo');

$app->get('/', function ($request, $response) {
    return $response->redirectToRoute('foo');
});
```

render()
--------

`public function render(string $template, array $params = []): string`

Attempts to render the `$template` with `$params` using the ViewManager. If a strategy 
is not available then the Tonis fallback strategy is used. 

```php
$app->get('/article/{id}', function ($request, $response) {
    // output is render result of `article/view` template
    return $response->render('article/view', $request->getParams());
});
```
