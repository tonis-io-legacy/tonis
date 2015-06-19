<?php
namespace Tonis\Router;

use Psr\Http\Message\RequestInterface;
use Tonis\Router\Exception\InvalidRouteException;
use Tonis\Router\Exception\RouteDoesNotExistException;
use Tonis\Router\Exception\RouteExistsException;

final class RouteCollection implements \ArrayAccess, \Countable, \Iterator
{
    /** @var Route[] */
    private $routes = [];

    /**
     * @param string $path
     * @param mixed $handler
     * @param string|null $name
     * @return Route
     * @throws Exception\RouteExistsException
     */
    public function add($path, $handler, $name = null)
    {
        $route = new Route($path, $handler);
        $this->offsetSet($name, $route);
        return $route;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->routes[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new RouteDoesNotExistException($offset);
        }
        return $this->routes[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Route) {
            throw new InvalidRouteException;
        }
        if (null === $offset) {
            $this->routes[] = $value;
        } elseif (!isset($this->routes[$offset])) {
            $this->routes[$offset] = $value;
        } else {
            throw new RouteExistsException($offset);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new RouteDoesNotExistException($offset);
        }
        unset($this->routes[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->routes);
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
        next($this->routes);
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
        return $this->key() !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->routes);
    }
}
