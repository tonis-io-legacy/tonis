<?php
namespace Tonis\Exception;

use Zend\Diactoros\Response\EmitterInterface;

class InvalidEmitter extends \LogicException
{
    public function __construct()
    {
        parent::__construct('Emitters should implement ' . EmitterInterface::class);
    }
}
