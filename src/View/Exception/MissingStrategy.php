<?php
namespace Tonis\View\Exception;

final class MissingStrategy extends \InvalidArgumentException
{
    public function __construct($name)
    {
        parent::__construct(sprintf('No strategy with name "%s" has been registered', $name));
    }
}
