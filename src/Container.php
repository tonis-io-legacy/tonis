<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Container\Container as LeagueContainer;

class Container extends LeagueContainer implements ContainerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
