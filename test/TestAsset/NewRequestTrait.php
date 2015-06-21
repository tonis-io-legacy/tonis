<?php
namespace Tonis\TestAsset;

use Zend\Diactoros\ServerRequestFactory;

trait NewRequestTrait
{
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
