<?php
namespace Tonis\Exception;

class MissingRouteParam extends \LogicException
{
    public function __construct($route, $param)
    {
        parent::__construct(sprintf(
            'Route "%s" requires param "%s" but it was missing',
            $route,
            $param
        ));
    }
}
