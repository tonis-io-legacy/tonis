<?php
namespace Tonis\Exception;

class MissingRoute extends \InvalidArgumentException
{
    public function __construct($name)
    {
        parent::__construct(sprintf(
            'Route with name "%s" does not exist',
            $name
        ));
    }
}
