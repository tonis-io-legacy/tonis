<?php
namespace Tonis\Middleware;

use Tonis\App;
use Tonis\Http\Request;
use Tonis\Http\Response;
use Tonis\Router\Router;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $app    = $request->app();
        $result = $this->configure($app);

        if ($result instanceof Router) {
            return $result($request, $response, $next);
        }

        return $next ? $next($request, $response) : $response;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(App $app)
    {
    }
}
