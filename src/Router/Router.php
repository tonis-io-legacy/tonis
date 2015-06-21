<?php
namespace Tonis\Router;

use FastRoute\DataGenerator;
use FastRoute\Dispatcher as FastDispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std as StdRouteParser;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Http\ResponseInterface;

class Router
{
    /** @var RouteCollector */
    private $routeCollector;

    public function __construct()
    {
        $this->routeCollector = new RouteCollector(new StdRouteParser, new DataGenerator\GroupCountBased);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $dispatcher = $this->getDispatcher();
        $result = $dispatcher->dispatch($request);

        list($code, $handler, $params) = $result;

        if ($code == FastDispatcher::NOT_FOUND || $code == FastDispatcher::METHOD_NOT_ALLOWED) {
            return $next ? $next($request, $response) : $response;
        }

        foreach ((array) $params as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        return $handler($request, $response, $next);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function get($route, $handler)
    {
        return $this->add('GET', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function post($route, $handler)
    {
        return $this->add('POST', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function patch($route, $handler)
    {
        return $this->add('PATCH', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function put($route, $handler)
    {
        return $this->add('PUT', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function delete($route, $handler)
    {
        return $this->add('DELETE', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function head($route, $handler)
    {
        return $this->add('HEAD', $route, $handler);
    }

    /**
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function options($route, $handler)
    {
        return $this->add('OPTIONS', $route, $handler);
    }

    /**
     * @param array|string $methods
     * @param string $route
     * @param string $handler
     * @return self
     */
    public function add($methods, $route, $handler)
    {
        $this->routeCollector->addRoute($methods, $route, $handler);
        return $this;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        $fastDispatcher = new FastDispatcher\GroupCountBased($this->getRouteCollector()->getData());
        return new Dispatcher($fastDispatcher);
    }

    /**
     * @return RouteCollector
     */
    public function getRouteCollector()
    {
        return $this->routeCollector;
    }
}
