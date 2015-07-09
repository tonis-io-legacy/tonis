<?php
namespace Tonis\Router;

use FastRoute\DataGenerator\GroupCountBased as RouteGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use Tonis\Http\Request;
use Tonis\Http\Response;

final class Router
{
    /** @var RouteCollector */
    private $collector;
    /** @var array */
    private $middleware = [];
    /** @var RouteMap */
    private $routeMap;

    /**
     * @param RouteMap $routeMap
     */
    public function __construct(RouteMap $routeMap = null)
    {
        $this->collector = new RouteCollector(new RouteParser, new RouteGenerator);
        $this->routeMap  = $routeMap ?: new RouteMap;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
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

        $request = $request->withAttribute('params', $params);

        foreach ($params as $key => $value) {
            $request[$key] = $value;
        }

        // Custom middleware handler.
        // We have to iterate through middleware as it was added so that they retain the proper ordering.
        // We have to check if the middleware is a route and if it was the route matched during dispatching.
        // If so, we can call the route handler, otherwise, we need to skip over it.
        $middleware = $this->middleware;
        $callable   = function ($request, $response) use (&$callable, &$middleware, $route, $next) {
            /** @var callable $layer */
            $layer = array_shift($middleware);

            if (false === $layer) {
                return $next($request, $response);
            }

            if ($layer instanceof Route) {
                if ($layer !== $route) {
                    return $callable($request, $response, $next);
                }

                $layer = $route->getHandler();
                return $layer($request, $response);
            }

            return $layer($request, $response, $callable);
        };

        return $callable($request, $response);
    }

    /**
     * Adds query handlers to a queue. The queue is processed at invocation by RelayPHP.
     *
     * @param string $param
     * @param callable $handler
     */
    public function query($param, $handler)
    {
        $param = new QueryParam($param, $handler);
        $this->middleware[] = $param;
    }

    /**
     * Adds param handlers to a queue. The queue is processed at invocation by RelayPHP.
     *
     * @param string $param
     * @param callable $handler
     */
    public function param($param, $handler)
    {
        $param = new RouteParam($param, $handler);
        $this->middleware[] = $param;
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function get($path, $handler)
    {
        return $this->route('GET', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function post($path, $handler)
    {
        return $this->route('POST', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function patch($path, $handler)
    {
        return $this->route('PATCH', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function put($path, $handler)
    {
        return $this->route('PUT', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function delete($path, $handler)
    {
        return $this->route('DELETE', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function head($path, $handler)
    {
        return $this->route('HEAD', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function options($path, $handler)
    {
        return $this->route('OPTIONS', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function any($path, $handler)
    {
        return $this->route(['GET', 'POST', 'PATCH', 'PUT', 'HEAD', 'DELETE', 'OPTIONS'], $path, $handler);
    }

    /**
     * @param string|string[] $methods
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function route($methods, $path, $handler)
    {
        $route = $this->routeMap->add($path, $handler);
        $this->collector->addRoute($methods, $path, $route);
        $this->middleware[] = $route;

        return $route;
    }

    /**
     * @param callable $middleware
     */
    public function add($middleware)
    {
        $this->middleware[] = $middleware;
    }
}
