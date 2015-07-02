<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Container\Container as LeagueContainer;

class Container extends LeagueContainer implements ContainerInterface
{
    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return $this->isRegistered($id) || $this->isSingleton($id);
    }
}
