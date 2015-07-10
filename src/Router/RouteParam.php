<?php
namespace Tonis\Router;

use Tonis\Http\Request;
use Tonis\Http\Response;

final class RouteParam extends AbstractParam
{
    /**
     * {@inheritDoc}
     */
    public function shouldInvoke(Request $request, Response $response)
    {
        return isset($request[$this->param]);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(Request $request, Response $response)
    {
        return $request[$this->param];
    }
}
