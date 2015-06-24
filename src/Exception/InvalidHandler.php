<?php
namespace Tonis\Exception;

final class InvalidHandler extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid handler: must be callable');
    }
}
