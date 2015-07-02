<?php
namespace Tonis;

use FastRoute\DataGenerator\GroupCountBased as RouteGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use Relay\RelayBuilder;
use Tonis\Http\Request;
use Tonis\Http\Response;

final class Router
{
    /** @var RouteCollector */
    private $collector;
    /** @var array */
    private $middleware = [];
    /** @var array */
    private $paramHandlers = [];
    /** @var RelayBuilder */
    private $relayBuilder;
    /** @var RouteMap */
    private $routeMap;

    /**
     * @param RouteMap $routeMap
     */
    public function __construct(RouteMap $routeMap = null)
    {
        $this->collector    = new RouteCollector(new RouteParser, new RouteGenerator);
        $this->relayBuilder = new RelayBuilder;
        $this->routeMap     = $routeMap ?: new RouteMap;
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

        foreach ($params as $key => $value) {
            $request[$key] = $value;
        }

        $relay    = $this->relayBuilder->newInstance($this->middleware);
        $response = $relay($request, $response);

        return $this->paramHandler($request, $response, $next, $route->getHandler(), $params);
    }

    /**
     * @internal
     * @param Request $request
     * @param Response $response
     * @param callable $done
     * @param callable $route
     * @param array $params
     */
    public function paramHandler(
        Request $request,
        Response $response,
        callable $done,
        callable $route,
        array $params
    ) {
        $handler = function ($request, $response) use (&$handler, $done, $route, &$params) {
            // the params have been exhausted, we can call the route
            if (empty($params)) {
                return $route($request, $response, $done);
            }

            $name = key($params);
            array_shift($params);

            // no param handlers, we can call the route
            if (!isset($this->paramHandlers[$name])) {
                return $route($request, $response, $done);
            }

            $builder  = $this->relayBuilder;
            $relay    = $builder->newInstance($this->paramHandlers[$name]);
            $response = $relay($request, $response);

            return $handler($request, $response);
        };

        return $handler($request, $response);
    }

    /**
     * Adds param handlers to a queue. The queue is processed at invocation by RelayPHP.
     *
     * @param string $param
     * @param callable $handler
     */
    public function param($param, $handler)
    {
        $this->paramHandlers[$param][] = $handler;
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
     * @return Route*
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
