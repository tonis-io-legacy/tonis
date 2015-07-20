<?php
namespace Tonis\Router;

final class Route
{
    /** @var string */
    private $path;
    /** @var callable */
    private $handler;
    /** @var string|null */
    private $name;

    /**
     * @param string $path
     * @param mixed  $handler
     */
    public function __construct($path, $handler)
    {
        $this->path    = $path;
        $this->handler = $handler;
    }

    /**
     * accessor/mutator for name.
     *
     * @param null|string $name
     * @return null|string
     */
    public function name($name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return callable
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
