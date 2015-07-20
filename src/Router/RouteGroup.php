<?php
namespace Tonis\Router;

final class RouteGroup implements RouterInterface
{
    /** @var string */
    private $prefix;

    /**
     * @param Router $router
     * @param string $prefix
     */
    public function __construct(Router $router, $prefix)
    {
        $this->prefix = $prefix;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function group($prefix, callable $func)
    {
        $group = new RouteGroup($this->router, $this->prefix . $prefix);
        $func($group);
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, $handler)
    {
        return $this->router->get($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $handler)
    {
        return $this->router->post($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function patch($path, $handler)
    {
        return $this->router->patch($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $handler)
    {
        return $this->router->put($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $handler)
    {
        return $this->router->delete($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function head($path, $handler)
    {
        return $this->router->head($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function options($path, $handler)
    {
        return $this->router->options($this->prefix . $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function any($path, $handler)
    {
        return $this->router->any($this->prefix . $path, $handler);
    }
}
