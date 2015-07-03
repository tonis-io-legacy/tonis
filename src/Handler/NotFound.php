<?php
namespace Tonis\Handler;

use Tonis\Http\Request;
use Tonis\Http\Response;

final class NotFound implements NotFoundInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(Request $request, Response $response)
    {
        return $response
            ->withStatus(404)
            ->render('error/404', ['request' => $request]);
    }
}
