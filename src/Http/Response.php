<?php
namespace Tonis\Http;

use Tonis\Response\RenderResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\Http\Response as StratigilityResponse;

final class Response extends StratigilityResponse
{
    /**
     * Returns a response with a proper status code and Location header. If permanent
     * is true, a 301 status code is used instead of 302.
     *
     * @param string $url
     * @param bool   $permanent
     * @return self
     */
    public function redirect($url, $permanent = false)
    {
        return $this
            ->withHeader('Location', $url)
            ->withStatus($permanent ? 301 : 302);
    }

    /**
     * Redirects to a named route.
     *
     * @see \Tonis\Http\Response::redirect()
     * @param string     $route
     * @param array      $params
     * @param bool|false $permanent
     * @return self
     */
    public function redirectToRoute($route, array $params = [], $permanent = false)
    {
        throw new \RuntimeException('This feature is not yet implemented');
    }

    /**
     * Creates a new JsonResponse from input data.
     *
     * @param mixed $data
     * @param int   $status
     * @param array $headers
     * @param int   $encodingOptions
     * @return Response
     */
    public function json($data, $status = 200, array $headers = [], $encodingOptions = 15)
    {
        return new JsonResponse($data, $status, $headers, $encodingOptions);
    }

    /**
     * Creates a new RenderResponse for the RendererMiddleware.
     *
     * @param string $template
     * @param array  $params
     * @return string
     */
    public function render($template, array $params = [])
    {
        return new RenderResponse($template, $params);
    }
}