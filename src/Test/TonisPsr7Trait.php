<?php
namespace Tonis\Test;

use Tonis\App;
use Tonis\Http\Request as TonisRequest;
use Tonis\Http\Response as TonisResponse;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

trait TonisPsr7Trait
{
    /**
     * @return TonisResponse
     */
    protected function newTonisResponse()
    {
        return new TonisResponse(new App(), new Response());
    }

    /**
     * @param string $path
     * @param array $server
     * @return TonisRequest
     */
    protected function newTonisRequest($path, array $server = [])
    {
        $server['REQUEST_URI'] = $path;
        $server = array_merge($_SERVER, $server);

        return new TonisRequest(new App(), $this->newRequest($path, $server));
    }

    /**
     * @param string $path
     * @param array $server
     * @return \Zend\Diactoros\ServerRequest
     */
    protected function newRequest($path, array $server = [])
    {
        $server['REQUEST_URI'] = $path;
        $server = array_merge($_SERVER, $server);

        return ServerRequestFactory::fromGlobals($server);
    }
}
