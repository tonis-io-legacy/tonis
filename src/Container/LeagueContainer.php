<?php
namespace Tonis\Container;

use Interop\Container\ContainerInterface;
use League\Container\Container;

class LeagueContainer extends Container implements ContainerInterface
{
    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
