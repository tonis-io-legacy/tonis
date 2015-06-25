<?php
namespace Tonis;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tonis\Exception\InvalidHandler;

class Route
{
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $handler = $this->handler;

        if (!is_callable($handler)) {
            throw new InvalidHandler;
        }

        return $handler($request, $response, $next);
    }
}
