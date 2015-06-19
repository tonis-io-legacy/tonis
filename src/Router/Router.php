<?php
namespace Tonis\Router;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Router
{
    /** @var Dispatcher */
    private $dispatcher;
    /** @var Collection */
    private $collection;

    public function __construct()
    {
        $this->dispatcher = new Dispatcher;
        $this->collection = new Collection;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     * @throws Exception\InvalidHandlerException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $match = $this->match($request);

        if (empty($match)) {
            return $next ? $next($request, $response) : $response;
        }

        $request = $request->withAttribute('handler', $match[0]);

        foreach ($match[1] as $key => $value) {
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
     * @param RequestInterface $request
     * @return array
     */
    public function match(RequestInterface $request)
    {
        foreach ($this->collection as $route) {
            $methods = $route->getMethods();
            if (!empty($methods)) {
                $success = false;

                foreach ($methods as $allowed) {
                    if (0 === strcasecmp($request->getMethod(), $allowed)) {
                        $success = true;
                        break;
                    }
                }

                if (!$success) {
                    return [];
                }
            }

            if (preg_match('@^' . $route->getRegex() . '$@', $request->getUri()->getPath(), $params)) {
                foreach ($params as $index => $param) {
                    if (is_numeric($index)) {
                        unset($params[$index]);
                    }
                }
                return [$route->getHandler(), $params];
            }
        }
        return [];
    }

    /**
     * @param string $path
     * @param mixed $handler
     * @param string|null $name
     * @return Route
     */
    public function add($path, $handler, $name = null)
    {
        return $this->collection->add($path, $handler, $name);
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
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
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
        return $this->collection->add($path, $handler, $name)->methods([$method]);
    }
}
