<?php
namespace Tonis;

use FastRoute\RouteParser\Std as RouteParser;
use Tonis\Exception;

class RouteMap implements \Countable, \Iterator
{
    /** @var Route[] */
    private $routes = [];
    /** @var Route[] */
    private $nameCache = [];
    /** @var RouteParser  */
    private $routeParser;

    /**
     * Adds a route to the map and resets the name cache.
     *
     * @param string $path
     * @param callable $handler
     * @return Route
     */
    public function add($path, $handler)
    {
        $route             = new Route($path, $handler);
        $this->routes[]    = $route;
        $this->nameCache   = [];
        $this->routeParser = new RouteParser;

        return $route;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public function assemble($name, $params = [])
    {
        if (!$this->hasRoute($name)) {
            throw new Exception\MissingRoute($name);
        }

        $this->buildNameCache();

        $route       = $this->nameCache[$name];
        $path        = $route->getPath();
        $routeParts  = $this->routeParser->parse($path);
        $useOptional = false;

        // $routeParts contains at most two entries
        // the first is the route parsed without the optional segment
        // the second is the route with the optional segment
        // this determines which part we should use to assemble the array
        if (isset($routeParts[1])) {
            $useOptional = true;
            foreach ($routeParts[1] as $part) {
                if (is_string($part)) {
                    continue;
                }

                if (isset($params[$part[0]])) {
                    continue;
                }

                $useOptional = false;
                break;
            }
        }

        return $this->assembleFromParts($name, $params, $useOptional ? $routeParts[1] : $routeParts[0]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRoute($name)
    {
        $this->buildNameCache();
        return isset($this->nameCache[$name]);
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Iterates through routes and builds a name cache.
     */
    private function buildNameCache()
    {
        if (!empty($this->nameCache)) {
            return;
        }
        foreach ($this as $route) {
            if (!$route->name()) {
                continue;
            }
            $this->nameCache[$route->name()] = $route;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return null !== key($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->routes);
    }

    /**
     * @param string $name
     * @param array $params
     * @param array $parts
     * @return string
     */
    private function assembleFromParts($name, array $params, array $parts)
    {
        $url = '';

        foreach ($parts as $part) {
            if (is_string($part)) {
                $url .= $part;
                continue;
            }

            if (!isset($params[$part[0]])) {
                throw new Exception\MissingRouteParam($name, $part[0]);
            }

            $url .= $params[$part[0]];
        }

        return $url;
    }
}
