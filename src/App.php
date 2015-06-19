<?php
namespace Tonis\Web;

use Interop\Container\ContainerInterface;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tonis\Di\Container;
use Tonis\Router\Router;
use Tonis\View\ViewManager;
use Zend\Stratigility\MiddlewarePipe;

final class App
{
    /** @var MiddlewarePipe */
    private $pipe;

    public function __construct()
    {
        $this->pipe = new MiddlewarePipe();
        $this->serviceContainer = new Container();
        $this->viewManager = new ViewManager();
    }

    /**
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $next = null)
    {
        $req = $this->decorateRequest($req);
        $res = $this->decorateResponse($res);

        return $this->pipe->__invoke($req, $res, $next);
    }

    /**
     * @return Router
     */
    public function createRouter()
    {
        return new Router;
    }

    /**
     * Proxies to MiddlewarePipe::pipe.
     *
     * @param string $path
     * @param null $middleware
     * @return MiddlewarePipe
     */
    public function pipe($path, $middleware = null)
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
     * @return ViewManager
     */
    public function getViewManager()
    {
        return $this->viewManager;
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
