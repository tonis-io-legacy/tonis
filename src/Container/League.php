<?php
namespace Tonis\Container;

use Interop\Container\ContainerInterface;
use League\Container\Container as LeagueContainer;

class League extends LeagueContainer implements ContainerInterface
{
    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
