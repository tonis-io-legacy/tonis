<?php
namespace Tonis\Middleware;

use Tonis\App;
use Tonis\Http\Request;
use Tonis\Http\Response;

interface MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null);

    /**
     * @param App $app
     * @return \Tonis\Router|null
     */
    public function configure(App $app);
}
