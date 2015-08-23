<?php
namespace Tonis\Router;

use Psr\Http\Message\ServerRequestInterface;
use Tonis\Resolver\ResolverInterface;
use Zend\Stratigility\Http\ResponseInterface;

final class Route
{
    /** @var string */
    private $path;
    /** @var callable */
    private $handlers;
    /** @var string|null */
    private $name;

    /**
     * @param string $path
     * @param mixed  $handlers
     */
    public function __construct($path, $handlers)
    {
        $this->path     = $path;
        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(
        ResolverInterface $resolver,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        $callable = function ($request, $response) use (&$callable, $next, $resolver) {
            if (empty($this->handlers)) {
                return $next($request, $response);
            }

            $handler = array_shift($this->handlers);
            $handler = $resolver->resolve($handler);

            return $handler($request, $response, $callable);
        };

        return $callable($request, $response, $next);
    }
}
