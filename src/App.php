<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tonis\Di\Container;
use Tonis\View\Strategy\PlatesStrategy;
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

    /**
     * Tonis is a middleware framework stack built on Stratigility. It provides
     * some quality of life features out of the box.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $defaults = [
            'env' => getenv('TONIS_ENV') ? getenv('TONIS_ENV') : 'development',
            'fallback_strategy' => null,
            'error_template' => 'error/error',
            'not_found_template' => 'error/404',
        ];
        $this->config = array_merge($defaults, $config);

        $this->pipe = new MiddlewarePipe();
        $this->serviceContainer = new Container();

        if (null === $this->config['fallback_strategy']) {
            $this->config['fallback_strategy'] = new PlatesStrategy(new Engine(__DIR__ . '/../view'));
        }

        $this->viewManager = new View\Manager($this->config['fallback_strategy']);
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
     * e.g., $router = $app->createRouter();
     *       $router->get(...)
     *
     *       $app->pipe('/foo', $router);
     *
     * @return Router\Router
     */
    public function createRouter()
    {
        return new Router\Router;
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
        $router = $this->createRouter();
        $router->$type($path, $handler);

        $this->pipe($router);
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
