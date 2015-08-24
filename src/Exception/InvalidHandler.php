<?php
namespace Tonis\Exception;

class InvalidHandler extends \LogicException
{
    public function __construct()
    {
        parent::__construct('All routing middleware must be callable');
    }
}
