<?php
namespace Tonis\Resolver;

interface ResolverInterface
{
    /**
     * @param mixed $handler
     * @return callable
     */
    public function resolve($handler);
}
