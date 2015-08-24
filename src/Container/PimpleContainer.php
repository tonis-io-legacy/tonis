<?php
namespace Tonis\Container;

use Interop\Container\ContainerInterface;
use Pimple\Container;

class PimpleContainer extends Container implements ContainerInterface
{
    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}