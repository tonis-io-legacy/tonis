<?php
namespace Tonis\Exception;

class NoContainerRegistered extends \LogicException
{
    public function __construct()
    {
        parent::__construct('No container was registered', 500);
    }
}