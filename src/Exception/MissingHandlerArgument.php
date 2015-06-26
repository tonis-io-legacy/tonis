<?php
namespace Tonis\Exception;

final class MissingHandlerArgument extends \LogicException
{
    public function __construct($name)
    {
        parent::__construct(sprintf('Handler is missing argument "%s" but it is required', $name));
    }
}
