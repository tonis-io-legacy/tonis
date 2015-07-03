<?php
namespace Tonis\Handler;

use Exception;
use Tonis\Http\Request;
use Tonis\Http\Response;

final class Error implements ErrorInterface
{
    /**
     * {@inheritDoc)
     */
    public function __invoke(Request $request, Response $response, Exception $exception)
    {
        $statuCode = 500;

        if ($exception->getCode() >= 400 && $exception->getCode() < 600) {
            $statuCode = $exception->getCode();
        }

        $response = $response->withStatus($statuCode);

        $vars = [
            'request'   => $request,
            'response'  => $response,
            'exception' => $exception,
        ];

        return $response->render('error/error', $vars);
    }
}
