<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\RelayBuilder;

final class App
{
    /** @var ContainerInterface  */
    private $container;
    /** @var callable[] */
    private $middleware = [];
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

        $this->container    = $container;
        $this->relayBuilder = new RelayBuilder();
        $this->view         = $container->get(View\Manager::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $done = null)
    {
        $this->middleware[] = $done?: new FinalHandler;

        $request  = $this->decorateRequest($request);
        $response = $this->decorateResponse($response);
        $relay    = $this->relayBuilder->newInstance($this->middleware);
        $result   = $relay($request, $response);

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
        return $this->container->get(Router::class);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function get($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function post($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function put($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function patch($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function delete($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function options($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function head($path, $handler)
    {
        return $this->httpVerb($path, $handler, __FUNCTION__);
    }

    /**
     * Proxies to MiddlewarePipe::pipe.
     *
     * @param callable $handler
     */
    public function add($handler)
    {
        $this->middleware[] = $handler;
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
        $route  = $router->$type($path, $handler);

        $this->add($router);

        return $route;
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
