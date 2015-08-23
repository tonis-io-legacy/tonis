<?php
namespace Tonis\View;

use Psr\Http\Message\ServerRequestInterface;
use Tonis\MiddlewareInterface;
use Tonis\Response\RenderResponse;
use Zend\Stratigility\Http\ResponseInterface;

class RendererMiddleware implements MiddlewareInterface
{
    /** @var RendererInterface */
    private $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Inspects the response for a RenderResponse and if present renders
     * the output using the registered renderer.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next($request, $response);

        if ($response instanceof RenderResponse) {
            $body = $this->renderer->render($response->getTemplate(), $response->getVariables()->toArray());
            $response->getBody()->write($body);
        }

        return $response;
    }
}