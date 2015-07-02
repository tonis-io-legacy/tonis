<?php
namespace Tonis;

use Exception;

final class FinalHandler
{
    /**
     * Checks if $error is present and responds with error or 404 appropriately.
     *
     * @param Http\Request $request
     * @param Http\Response $response
     * @param mixed $error
     * @return Http\Response
     */
    public function __invoke(Http\Request $request, Http\Response $response, $error = null)
    {
        if ($error) {
            return $this->handleError($error, $request, $response);
        }
        return $this->handle404($request, $response);
    }

    /**
     * Handles an error. Similar to Stratigilities except we're rendering a template.
     *
     * @param mixed $error
     * @param Http\Request $request
     * @param Http\Response $response
     * @return Http\Response
     */
    private function handleError($error, Http\Request $request, Http\Response $response)
    {
        $response = $response->withStatus($this->getStatusCode($error, $response));
        $vars = [
            'request' => $request,
            'response' => $response
        ];

        if ($error instanceof Exception) {
            $vars['exception'] = $error;
            $vars['message'] = $error->getMessage();
        } else {
            $vars['message'] = $error;
        }

        return $response->render('error/error', $vars);
    }

    /**
     * Handles a 404. Similar to Stratigility except we're rendering a template.
     *
     * @param Http\Request $request
     * @param Http\Response $response
     * @return Http\Response
     */
    private function handle404(Http\Request $request, Http\Response $response)
    {
        return $response
            ->withStatus(404)
            ->render('error/404', ['request' => $request]);
    }

    private function getStatusCode($error, Http\Response $response)
    {
        // Exceptions with valid HTTP code
        if ($error instanceof \Exception && ($error->getCode() >= 400 && $error->getCode() < 600)) {
            return $error->getCode();
        }

        $statusCode = $response->getStatusCode();
        if (!$statusCode || $statusCode < 400 || $statusCode >= 600) {
            $statusCode = 500;
        }
        return $statusCode;
    }
}
