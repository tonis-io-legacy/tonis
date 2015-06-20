<?php
namespace Tonis\Middleware;

use Tonis\App;
use Tonis\Http\Request;
use Tonis\Http\Response;
use Tonis\Router\Router;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $req
     * @param Response $res
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $req, Response $res, callable $next = null)
    {
        $app    = $req->app();
        $result = $this->configure($app);

        if ($result instanceof Router) {
            return $result($req, $res, $next);
        }

        return $next ? $next($req, $res) : $res;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(App $app)
    {
    }
}
