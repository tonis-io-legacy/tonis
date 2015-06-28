<?php
namespace Tonis;

use FastRoute\DataGenerator\GroupCountBased as RouteGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use Tonis\Http\Request;
use Tonis\Http\Response;
use Zend\Stratigility\MiddlewarePipe;

final class Router
{
    /** @var RouteCollector */
    private $collector;
    /** @var MiddlewarePipe[] */
    private $paramHandlers = [];
    /** @var MiddlewarePipe */
    private $pipe;

    public function __construct()
    {
        $this->pipe       = new MiddlewarePipe;
        $this->collector  = new RouteCollector(new RouteParser, new RouteGenerator);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $pipe = $this->pipe;
        $pipe->pipe([$this, 'dispatchHandler']);

        return $pipe($request, $response, $next);
    }

    /**
     * @internal
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function dispatchHandler(Request $request, Response $response, callable $next)
    {
        $dispatcher = new Dispatcher($this->collector->getData());
        $result     = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        $code       = $result[0];
        $handler    = isset($result[1]) ? $result[1] : null;
        $params     = isset($result[2]) ? $result[2] : [];

        if ($code != Dispatcher::FOUND) {
            return $next ? $next($request, $response) : $response;
        }

        foreach ($params as $key => $value) {
            $request[$key] = $value;
        }

        return $this->paramHandler($request, $response, $next, $handler, $params);
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
        $handler = function ($request, $response, $err = null) use (&$handler, $done, $route, &$params) {
            if (empty($params)) {
                if (null !== $err) {
                    return $done($request, $response, $err);
                }
                return $route($request, $response);
            }

            $name  = key($params);
            array_shift($params);

            if (!isset($this->paramHandlers[$name])) {
                return $route($request, $response);
            }

            return $this->paramHandlers[$name]($request, $response, $handler);
        };

        return $handler($request, $response);
    }

    /**
     * @param string $param
     * @param callable $handler
     */
    public function param($param, $handler)
    {
        if (!isset($this->paramHandlers[$param])) {
            $this->paramHandlers[$param] = new MiddlewarePipe;
        }
        $this->paramHandlers[$param]->pipe($handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function get($path, $handler)
    {
        $this->route('GET', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function post($path, $handler)
    {
        $this->route('POST', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function patch($path, $handler)
    {
        $this->route('PATCH', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function put($path, $handler)
    {
        $this->route('PUT', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function delete($path, $handler)
    {
        $this->route('DELETE', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function head($path, $handler)
    {
        $this->route('HEAD', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function options($path, $handler)
    {
        $this->route('OPTIONS', $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return Router
     */
    public function any($path, $handler)
    {
        $this->route(['GET', 'POST', 'PATCH', 'PUT', 'HEAD', 'DELETE', 'OPTIONS'], $path, $handler);
    }

    /**
     * @param string|string[] $methods
     * @param string $path
     * @param callable $handler
     */
    public function route($methods, $path, $handler)
    {
        $this->collector->addRoute($methods, $path, $handler);
    }

    /**
     * @param string|callable $pathOrMiddleware
     * @param null|callable $middleware
     */
    public function add($pathOrMiddleware, $middleware = null)
    {
        $this->pipe->pipe($pathOrMiddleware, $middleware);
    }
}
