<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tonis\Di\Container;
use Zend\Stratigility\MiddlewarePipe;

final class App
{
    /** @var array */
    private $config = [];
    /** @var MiddlewarePipe */
    private $pipe;
    /** @var ContainerInterface  */
    private $serviceContainer;
    /** @var View\Manager */
    private $viewManager;

    public function __construct(ContainerInterface $container = null)
    {
        if (null === $container) {
            $container = new Container;
        }

        $this->pipe = new MiddlewarePipe();
        $this->serviceContainer = $container;
        $this->viewManager = $container->get(View\Manager::class);
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

        return $this->pipe->__invoke($req, $res, $out ?: new FinalHandler());
    }

    /**
     * @param string $key
     * @param null $value
     * @return mixed
     */
    public function config($key, $value = null)
    {
        if (null !== $value) {
            $this->config[$key] = $value;
        }
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * Routers are middleware enabled and can be piped back into Tonis.
     *
     * e.g., $router = $app->router();
     *       $router->get(...)
     *
     *       $app->pipe('/foo', $router);
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
        return $this->pipe->pipe($path, $middleware);
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
        $this->pipe->pipe($this->router()->$type($path, $handler));
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
