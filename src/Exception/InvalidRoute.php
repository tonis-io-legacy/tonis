<?php
namespace Tonis\Exception;

class InvalidRoute extends \LogicException
{
    public function __construct()
    {
        parent::__construct('An invalid route was registered for dispatching');
    }
}
