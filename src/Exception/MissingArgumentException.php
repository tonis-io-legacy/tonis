<?php
namespace Tonis\Exception;

final class MissingArgumentException extends \RuntimeException
{
    public function __construct($name)
    {
        parent::__construct(sprintf('Argument "%s" is missing but is required', $name));
    }
}
