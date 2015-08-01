<?php
namespace Tonis\Router;

interface GroupInterface
{
    /**
     * @param  string  $name
     * @param callable $func
     * @return GroupInterface
     */
    public function group($name, callable $func);
}
