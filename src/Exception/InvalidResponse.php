<?php
namespace Tonis\Exception;

class InvalidResponse extends \LogicException
{
    public function __construct()
    {
        parent::__construct('An invalid response was returned from handler');
    }
}
