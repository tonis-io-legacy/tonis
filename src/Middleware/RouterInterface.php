<?php
namespace Tonis\Middleware;

use Tonis\Http\Request;
use Tonis\Http\Response;

interface RouterInterface
{
    /**
     * Interface defining router level middleware specific to Tonis.
     *
     * Takes a \Tonis\Http\Request and a \Tonis\Http\Response. Implementation MUST
     * return a \Tonis\Http\Response
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response);
}
