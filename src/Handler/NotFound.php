<?php
namespace Tonis\Handler;

use Tonis\Http\Request;
use Tonis\Http\Response;

final class NotFound implements NotFoundInterface
{
    /**
     * Checks if $error is present and responds with error or 404 appropriately.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        return $response
            ->withStatus(404)
            ->render('error/404', ['request' => $request]);
    }
}
