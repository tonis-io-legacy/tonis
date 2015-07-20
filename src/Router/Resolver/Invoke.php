<?php
namespace Tonis\Router\Resolver;

final class Invoke implements ResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve($handler)
    {
        if (is_string($handler) && class_exists($handler)) {
            return new $handler;
        }
        return $handler;
    }
}
