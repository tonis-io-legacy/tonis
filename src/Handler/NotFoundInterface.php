<?php
namespace Tonis\Handler;

use Tonis\Http\Request;
use Tonis\Http\Response;

interface NotFoundInterface
{
    /**
     * NotFound interface only accepts two arguments, $request and $response, and terminates the life-cycle.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response);
}
