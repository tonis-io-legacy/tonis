<?php
namespace Tonis\Router\Resolver;

use Interop\Container\ContainerInterface;

/**
 * @covers \Tonis\Router\Resolver\Container
 */
final class Container implements ResolverInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($handler)
    {
        if (is_string($handler)) {
            return $this->container->get($handler);
        } elseif (is_array($handler) && isset($handler[0]) && is_string($handler[0])) {
            $handler[0] = $this->container->get($handler[0]);
            return $handler;
        }
        return $handler;
    }
}
