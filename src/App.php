<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

final class App
{
    /** @var Router */
    private $currentRouter;
    /** @var MiddlewarePipe */
    private $stratigility;
    /** @var ContainerInterface  */
    private $serviceContainer;
    /** @var View\Manager */
    private $viewManager;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        if (null === $container) {
            $container = new Container();
            $container->addServiceProvider(new ServiceProvider);
        }

        $this->stratigility     = new MiddlewarePipe();
        $this->serviceContainer = $container;
        $this->viewManager      = $container->get(View\Manager::class);
    }

    /**
     * Decorates the request and response so they are aware of Tonis. Additionally, register some helper
     * middleware if enabled.
     *
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @param callable $out
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $out = null)
    {
        $req = $this->decorateRequest($req);
        $res = $this->decorateResponse($res);

        return $this->stratigility->__invoke($req, $res, $out ?: new FinalHandler());
    }

    /**
     * Routers are middleware enabled and can be piped back into Tonis.
     *
     * e.g., $router = $app->router();
     *       $router->get(...)
     *
     *       $app->stratigility('/foo', $router);
     *
     * @return Router
     */
    public function router()
    {
        $this->currentRouter = new Router;
        return $this->currentRouter;
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function get($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function post($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function put($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function patch($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function delete($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function options($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * @param string $path
     * @param callable $handler
     */
    public function head($path, $handler)
    {
        $this->addRouteVerb($path, $handler, __FUNCTION__);
    }

    /**
     * Proxies to MiddlewarePipe::pipe.
     *
     * @param string $path
     * @param null $middleware
     * @return MiddlewarePipe
     */
    public function add($path, $middleware = null)
    {
        return $this->stratigility->pipe($path, $middleware);
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @return View\Manager
     */
    public function getViewManager()
    {
        return $this->viewManager;
    }

    /**
     * @param string $path
     * @param callable $handler
     * @param string $type
     */
    private function addRouteVerb($path, $handler, $type)
    {
        $router = $this->currentRouter ?: $this->router();
        $router->$type($path, $handler);

        $this->stratigility->pipe($router);
    }

    /**
     * Decorates a request to add this app to it.
     *
     * @param ServerRequestInterface $req
     * @return ServerRequestInterface|Http\Request
     */
    private function decorateRequest(ServerRequestInterface $req)
    {
        if ($req instanceof Http\Request) {
            return $req;
        }
        return new Http\Request($this, $req);
    }

    /**
     * Decorates a response to add this app to it.
     *
     * @param ResponseInterface $res
     * @return ResponseInterface|Http\Response
     */
    private function decorateResponse(ResponseInterface $res)
    {
        if ($res instanceof Http\Response) {
            return $res;
        }
        return new Http\Response($this, $res);
    }
}
