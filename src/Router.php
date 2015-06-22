<?php
namespace Tonis;

use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Router
{
    public function __construct()
    {
        $this->routeCollector = new RouteCollector(new Std(), new DataGenerator\GroupCountBased());
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
        $result = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        $code = $result[0];
        $handler = isset($result[1]) ? $result[1] : null;
        $params = isset($result[2]) ? $result[2] : [];

        if ($code == Dispatcher::NOT_FOUND || $code == Dispatcher::METHOD_NOT_ALLOWED) {
            return $next ? $next($request, $response) : $response;
        }

        foreach ($params as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        if (!is_callable($handler)) {
            throw new Exception\InvalidHandler('Invalid handler');
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
