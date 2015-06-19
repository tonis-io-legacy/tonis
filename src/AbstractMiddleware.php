<?php
namespace Tonis\Web;

use Tonis\Router\Router;
use Tonis\Web\Http\Request;
use Tonis\Web\Http\Response;

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
