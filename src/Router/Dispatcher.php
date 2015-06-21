<?php
namespace Tonis\Router;

use FastRoute\Dispatcher as FastDispatcher;
use Psr\Http\Message\RequestInterface;

class Dispatcher
{
    /** @var FastDispatcher */
    private $fastDispatcher;

    /**
     * @param FastDispatcher $fastDispatcher
     */
    public function __construct(FastDispatcher $fastDispatcher)
    {
        $this->fastDispatcher = $fastDispatcher;
    }

    /**
     * Proxies to \FastRouter\Dispatcher::dispatch()
     *
     * @param RequestInterface $request
     * @return array
     */
    public function dispatch(RequestInterface $request)
    {
        return $this->fastDispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    }
}
