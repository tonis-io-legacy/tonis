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
    /** @var \SplQueue */
    private $middleware;
    /** @var RouteCollector */
    private $routeCollector;

    public function __construct()
    {
        $this->middleware = new \SplQueue;
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

        return (new RouteHandler($this->middleware, $handler))->__invoke($request, $response, $next);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function get($path, $handler)
    {
        return $this->route('GET', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function post($path, $handler)
    {
        return $this->route('POST', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function patch($path, $handler)
    {
        return $this->route('PATCH', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function put($path, $handler)
    {
        return $this->route('PUT', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function delete($path, $handler)
    {
        return $this->route('DELETE', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function head($path, $handler)
    {
        return $this->route('HEAD', $path, $handler);
    }

    /**
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function options($path, $handler)
    {
        return $this->route('OPTIONS', $path, $handler);
    }

    /**
     * @param string|string[] $methods
     * @param string $path
     * @param string $handler
     * @return self
     */
    public function route($methods, $path, $handler)
    {
        $route = new Route($handler);
        $this->add($route);
        $this->routeCollector->addRoute($methods, $path, $route);

        return $this;
    }

    public function add($middleware)
    {
        $this->middleware->enqueue($middleware);
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
