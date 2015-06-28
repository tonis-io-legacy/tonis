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

json()
------

`public function json(mixed $input): Tonis\Http\Response`

Encodes the input as JSON and sets the Content-Type header to application/json.

jsonp()
-------

`public function jsonp(mixed $input, string $callback): Tonis\Http\Response`

Encodes the input as JSONP and sets the Content-Type header to application/javascript.

render()
--------

`public function render(string $template, array $params = []): string`

Attempts to render the `$template` with `$params` using the ViewManager. If a strategy 
is not available then the Tonis fallback strategy is used. 
