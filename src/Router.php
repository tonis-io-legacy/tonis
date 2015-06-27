<?php
namespace Tonis;

use FastRoute\DataGenerator\GroupCountBased as RouteGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as RouteParser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

final class Router
{
    /** @var RouteCollector */
    private $routeCollector;
    /** @var MiddlewarePipe[] */
    private $paramPipes = [];
    /** @var MiddlewarePipe */
    private $routerPipe;

    public function __construct()
    {
        $this->paramPipe      = new MiddlewarePipe;
        $this->routerPipe     = new MiddlewarePipe;
        $this->routeCollector = new RouteCollector(new RouteParser, new RouteGenerator);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $routerPipe = $this->routerPipe;
        $routerPipe->pipe([$this, 'run']);

        return $routerPipe($request, $response, $next);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $dispatcher = $this->getDispatcher();
        $result     = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        $code       = $result[0];
        $route      = isset($result[1]) ? $result[1] : null;
        $params     = isset($result[2]) ? $result[2] : [];

        if ($code != Dispatcher::FOUND) {
            return $next ? $next($request, $response) : $response;
        }

        /** @var Route $route */
        $route->setParams($params);

        foreach ($params as $key => $value) {
            $request[$key] = $value;
        }

        $paramHandler = function ($request, $response, $err = null) use (&$params, &$paramHandler, $route, $next) {
            if (empty($params)) {
                if (null !== $err) {
                    return $next($request, $response, $err);
                }
                return $route($request, $response);
            }

            $name  = key($params);
            $param = array_shift($params);
            $pipe  = isset($this->paramPipes[$name]) ? $this->paramPipes[$name] : null;

            if (null === $pipe) {
                return $route($request, $response);
            }

            return $pipe($request, $response, $paramHandler);
        };


        return $paramHandler($request, $response);
    }

    public function param($param, $handler)
    {
        if (!isset($this->paramPipes[$param])) {
            $this->paramPipes[$param] = new MiddlewarePipe;
        }
        $this->paramPipes[$param]->pipe($handler);
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
        $route = new Route($handler);
        $this->routeCollector->addRoute($methods, $path, $route);
    }

    public function add($path, $middleware = null)
    {
        $this->routerPipe->pipe($path, $middleware);
    }

    /**
     * @return Dispatcher\GroupCountBased
     */
    public function getDispatcher()
    {
        return new Dispatcher\GroupCountBased($this->getRouteCollector()->getData());
    }

    /**
     * @return RouteCollector
     */
    public function getRouteCollector()
    {
        return $this->routeCollector;
    }
}
