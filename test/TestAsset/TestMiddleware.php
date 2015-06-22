<?php
namespace Tonis\TestAsset;

use Tonis\App;
use Tonis\Middleware\AbstractMiddleware;

class TestMiddleware extends AbstractMiddleware
{
    public function configure(App $app)
    {
        $router = $app->createRouter();
        $router->get('/', function() {
            return 'foo';
        });

        return $router;
    }
}