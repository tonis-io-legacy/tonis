<?php
namespace Tonis;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Http\ResponseInterface;

interface MiddlewareInterface
{
    /**
     * Interface defining application level middleware specific to Tonis.
     *
     * Takes a \Tonis\Http\Request, a \Tonis\Http\Response, and a callable $next. Implementation MAY
     * call the next middleware in the stack with $next($request, $reponse). Implementation MUST
     * return a \Tonis\Http\Response
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next);
}
