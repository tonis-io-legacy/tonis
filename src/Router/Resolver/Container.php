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
        }
        return $handler;
    }
}
