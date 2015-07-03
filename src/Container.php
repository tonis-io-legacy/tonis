<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Container\Container as LeagueContainer;

class Container extends LeagueContainer implements ContainerInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->addServiceProvider(new ServiceProvider);
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return $this->isRegistered($id) || $this->isSingleton($id);
    }
}
