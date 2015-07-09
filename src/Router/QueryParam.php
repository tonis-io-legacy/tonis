<?php
namespace Tonis\Router;

use Tonis\Http\Request;
use Tonis\Http\Response;

final class QueryParam extends AbstractParam
{
    /**
     * {@inheritDoc}
     */
    public function shouldInvoke(Request $request, Response $response)
    {
        return isset($request->getQueryParams()[$this->param]);
    }
}
