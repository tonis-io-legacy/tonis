<?php
namespace Tonis\Router\Resolver;

interface ResolverInterface
{
    /**
     * @param mixed $handler
     * @return mixed
     */
    public function resolve($handler);
}
