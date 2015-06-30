<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

final class App
{
    /** @var ContainerInterface  */
    private $container;
    /** @var View\Manager */
    private $view;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        if (null === $container) {
            $container = new Container();
            $container->addServiceProvider(new ServiceProvider);
        }

        $this->pipe      = new MiddlewarePipe;
        $this->container = $container;
        $this->view      = $container->get(View\Manager::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $done
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $done = null)
    {
        $request  = $this->decorateRequest($request);
        $response = $this->decorateResponse($response);
        $pipe     = $this->pipe;
        $done     = $done ?: new FinalHandler();
        $result   = $pipe($request, $response, $done);

        return $result instanceof ResponseInterface ? $result : $response;
    }

    /**
     * Routers are middleware and can be added to Tonis.
     *
     * e.g., $router = $app->router();
     *       $router->get(...)
     *
     *       $app->add('/foo', $router);
     *
     * @return Router
     */
    public function router()
    {
        return new Router;
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function get($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function post($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function put($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function patch($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function delete($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function options($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function head($path, $handler)
    {
        $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * Proxies to MiddlewarePipe::pipe.
     *
     * @param string $path
     * @param callable $handler
     */
    public function add($path, $handler = null)
    {
        $this->pipe->pipe($path, $handler);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return View\Manager
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param string $path
     * @param callable $handler
     * @param string $type
     *
     * @todo reuse router instance when possible
     */
    private function httpVerb($path, $handler, $type)
    {
        $router = $this->router();
        $router->$type($path, $handler);

        $this->add($router);
    }

    /**
     * Decorates a request to add this app to it.
     *
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface|Http\Request
     */
    private function decorateRequest(ServerRequestInterface $request)
    {
        if ($request instanceof Http\Request) {
            return $request;
        }
        return new Http\Request($this, $request);
    }

    /**
     * Decorates a response to add this app to it.
     *
     * @param ResponseInterface $response
     * @return ResponseInterface|Http\Response
     */
    private function decorateResponse(ResponseInterface $response)
    {
        if ($response instanceof Http\Response) {
            return $response;
        }
        return new Http\Response($this, $response);
    }
}
