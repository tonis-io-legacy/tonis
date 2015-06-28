Here is an example of a very basic Tonis app.

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Tonis\App;
$app->get('/', function($request, $response) {
    $response->end('Hello from Tonis');
});

$server = Zend\Diactoros\Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$server->listen();
```

You can run it with:

```php -S 127.0.0.1:8080 index.php```

Then, load [http://127.0.0.1:8080](http://127.0.0.1:8080) in a browser to see the output.
