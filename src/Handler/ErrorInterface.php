<?php
namespace Tonis\Handler;

use Exception;
use Tonis\Http\Request;
use Tonis\Http\Response;

interface ErrorInterface
{
    /**
     * Error accepts four arguments: $error, $request, $response, and $next. MUST return a Response.
     *
     * @param Request $request
     * @param Response $response
     * @param Exception $exception
     * @return Response
     */
    public function __invoke(Request $request, Response $response, Exception $exception);
}
