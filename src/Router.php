<?php
namespace Tonis\Router;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Router
{
    /** @var Dispatcher */
    private $dispatcher;
    /** @var RouteCollection */
    private $routeCollection;
    /** @var Rule\RuleInterface[] */
    private $rules;

    public function __construct()
    {
        $this->dispatcher = new Dispatcher;
        $this->routeCollection = new RouteCollection;
        $this->rules = [new Rule\Secure, new Rule\Method(), new Rule\Path()];
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $match = $this->match($request);

        if (!$match instanceof RouteMatch) {
            return $next ? $next($request, $response) : $response;
        }

        $request = $request->withAttribute('handler', $match->getRoute()->getHandler());

        foreach ($match->getParams() as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        return $this->dispatcher->__invoke($request, $response, $next);
    }

    /**
     * @param array $routes
     */
    public function fromArray(array $routes)
    {
        foreach ($routes as $name => $route) {
            if (is_int($name)) {
                $name = null;
            }

            $path = isset($route['path']) ? $route['path'] : $route[0];
            $handler = isset($route['handler']) ? $route['handler'] : $route[1];

            $this->add($path, $handler, $name);
        }
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws Exception\RouteDoesNotExistException
     */
    public function assemble($name, array $params = [])
    {
        $route = $this->routeCollection[$name];
        foreach ($route->getTokens() as $token) {
            list($name, $optional) = $token;
            if (!$optional && !isset($params[$name])) {
                throw new Exception\MissingParameterException($route->getPath(), $name);
            }
        }
        $replace = function ($matches) use ($params) {
            if (isset($params[$matches[2]])) {
                return $matches[1] . $params[$matches[2]];
            }
            return '';
        };
        return preg_replace_callback('@{([^A-Za-z]*)([A-Za-z]+)[?]?(?::[^}]+)?}@', $replace, $route->getPath());
    }

    /**
     * @param RequestInterface $request
     * @return null|RouteMatch
     */
    public function match(RequestInterface $request)
    {
        foreach ($this->routeCollection as $route) {
            $match = $this->matchRoute($request, $route);

            if ($match instanceof RouteMatch) {
                $this->lastMatch = $match;
                return $match;
            }
        }
        return null;
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string|null $name
     * @return Route
     */
    public function add($path, $handler, $name = null)
    {
        return $this->routeCollection->add($path, $handler, $name);
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $name
     * @return Route
     */
    public function get($path, $handler, $name = null)
    {
        return $this->addWithMethod($path, $handler, 'GET', $name);
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $name
     * @return Route
     */
    public function put($path, $handler, $name = null)
    {
        return $this->addWithMethod($path, $handler, 'PUT', $name);
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $name
     * @return Route
     */
    public function post($path, $handler, $name = null)
    {
        return $this->addWithMethod($path, $handler, 'POST', $name);
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $name
     * @return Route
     */
    public function patch($path, $handler, $name = null)
    {
        return $this->addWithMethod($path, $handler, 'PATCH', $name);
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $name
     * @return Route
     */
    public function delete($path, $handler, $name = null)
    {
        return $this->addWithMethod($path, $handler, 'DELETE', $name);
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    /**
     * @param RequestInterface $request
     * @param Route $route
     * @return bool
     */
    private function matchRoute(RequestInterface $request, Route $route)
    {
        $match = new RouteMatch($route);
        foreach ($this->rules as $rule) {
            if (!$rule($request, $match)) {
                return false;
            }
        }
        return $match;
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string $method
     * @param string $name
     * @return Route
     */
    private function addWithMethod($path, $handler, $method, $name = null)
    {
        return $this->routeCollection->add($path, $handler, $name)->methods([$method]);
    }
}
