<?php
namespace Tonis\Middleware;

use Tonis\Http\Request;
use Tonis\Http\Response;

interface AppInterface
{
    /**
     * Interface defining application level middleware specific to Tonis.
     *
     * Takes a \Tonis\Http\Request, a \Tonis\Http\Response, and a callable $next. Implementation MAY
     * call the next middleware in the stack with $next($request, $reponse). Implementation MUST
     * return a \Tonis\Http\Response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next);
}
