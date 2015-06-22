<?php
namespace Tonis\Exception;

class InvalidHandler extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid handler: must be callable');
    }
}
