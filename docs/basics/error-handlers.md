Overview
--------

Tonis supports error handlers for not found routes as well as exceptions. 

Exceptions
----------

Any exception during the request/response lifecycle will be handled by a instance of `Tonis\Handler\ErrorInterface`
which has the following signature.

```php
public function __invoke(Request $request, Response $response, Exception $exception);
```

Tonis uses `Tonis\Handler\Error` by default but you may override the default behavior.

Route Not Found
---------------

When no route matches the request an instance of `Tonis\Handler\NotFoundInterface` will be called which has the 
following signature.

```php
public function __invoke(Request $request, Response $response);
```

Tonis uses `Tonis\Handler\NotFound` by default but you may override the default behavior.
