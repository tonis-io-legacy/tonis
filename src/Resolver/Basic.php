<?php
namespace Tonis\Resolver;

use Interop\Container\ContainerInterface;
use Tonis\Exception;

final class Basic implements ResolverInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($handler)
    {
        if ($this->container) {
            if (is_string($handler) && $this->container->has($handler)) {
                $handler = $this->container->get($handler);
            } elseif (is_array($handler) && is_string($handler[0]) && $this->container->has($handler[0])) {
                $handler[0] = $this->container->get($handler[0]);
            }
        }

        if (is_string($handler) && class_exists($handler)) {
            $handler = new $handler();
        }

        if (!is_callable($handler)) {
            throw new Exception\InvalidHandler;
        }
        return $handler;
    }
}
