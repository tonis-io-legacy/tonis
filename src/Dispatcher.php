<?php
namespace Tonis\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Dispatcher
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $handler = $request->getAttribute('handler');
        $request = $request->withoutAttribute('handler');

        if (is_string($handler)) {
            $handler = new $handler;
        }
        if (!is_callable($handler)) {
            throw new Exception\InvalidHandlerException;
        }

        return $handler($request, $response, $next);
    }
}
