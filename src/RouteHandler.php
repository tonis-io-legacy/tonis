<?php
namespace Tonis;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class RouteHandler
{
    /** @var callable */
    private $callable;
    /** @var SplQueue */
    private $middleware;

    /**
     * @param SplQueue $middleware
     * @param callable $callable
     */
    public function __construct(SplQueue $middleware, callable $callable)
    {
        $this->middleware = $middleware;
        $this->callable = $callable;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->middleware->isEmpty()) {
            return $next($request, $response);
        }

        $middleware = $this->middleware->dequeue();

        if ($middleware instanceof Route && $middleware !== $this->callable) {
            $middleware = $this->middleware->dequeue();
        }

        return $middleware($request, $response, $this);
    }
}
