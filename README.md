# Tonis

[![Build Status](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/?branch=master)

Tonis is a PSR-7 compatible micro-framework and was built with an emphasis on flexibility and performance. Tonis features
a completely customizable request life-cycle and allows you to tune your application easily based on your needs.

Quick Start
-----------

```sh
composer require tonis/web
```

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$app = (new \Tonis\Web\Factory\TonisFactory)->createWeb();
$routes = $app->getRouter();

$routes->get('/hello/{name}', function ($name) {
    return sprintf('Hello %s, welcome to Tonis', $name);
});

$app->run();
```

Documentation
-------------

Full documentation can be found on the [Tonis GitHub page](https://docs.tonis.io) or in the `docs/` 
subdirectory.
