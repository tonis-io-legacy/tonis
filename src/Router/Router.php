<?php
namespace Tonis\Router;

use FastRoute\DataGenerator\GroupCountBased as RouteGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tonis\MiddlewareInterface;
use Tonis\Resolver;

final class Router implements MiddlewareInterface
{
    /** @var RouteCollector */
    private $collector;
    /** @var Resolver\ResolverInterface */
    private $resolver;

    /**
     * @param Resolver\ResolverInterface $resolver
     */
    public function __construct(Resolver\ResolverInterface $resolver = null)
    {
        $this->collector = new RouteCollector(new RouteParser, new RouteGenerator);
        $this->resolver  = $resolver ?: new Resolver\Basic();
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $dispatcher = new Dispatcher($this->collector->getData());
        $result     = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        $code       = $result[0];

        if ($code != Dispatcher::FOUND) {
            return $next($request, $response);
        }

        /** @var Route $route */
        $route  = $result[1];
        $params = $result[2];

        $request = $request
            ->withAttribute('params', $params)
            ->withAttribute('route', $route);

        return $route($this->resolver, $request, $response, $next);
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, $handlers)
    {
        return $this->route('GET', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $handlers)
    {
        return $this->route('POST', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $handlers)
    {
        return $this->route('PATCH', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $handlers)
    {
        return $this->route('PUT', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $handlers)
    {
        return $this->route('DELETE', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function head($path, $handlers)
    {
        return $this->route('HEAD', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function options($path, $handlers)
    {
        return $this->route('OPTIONS', $path, $handlers);
    }

    /**
     * {@inheritDoc}
     */
    public function any($path, $handlers)
    {
        return $this->route(['GET', 'POST', 'PATCH', 'PUT', 'HEAD', 'DELETE', 'OPTIONS'], $path, $handlers);
    }

    /**
     * @param string|string[] $methods
     * @param string          $path
     * @param callable        $handlers
     * @return Route
     */
    public function route($methods, $path, $handlers)
    {
        $route = new Route($path, $handlers);
        $this->collector->addRoute($methods, $path, $route);

        return $route;
    }
}
