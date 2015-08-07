<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\RelayBuilder;

final class App implements Router\RouterInterface
{
    /** @var ContainerInterface */
    private $container;
    /** @var Handler\ErrorInterface */
    private $errorHandler;
    /** @var Handler\NotFoundInterface */
    private $notFoundHandler;
    /** @var RelayBuilder */
    private $relayBuilder;
    /** @var callable[] */
    private $middleware = [];
    /** @var bool */
    private $debug = false;
    /** @var View\Manager */
    private $view;

    /**
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function __construct(ContainerInterface $container = null, $debug = false)
    {
        if (null === $container) {
            $container = new Container;
        }

        $this->relayBuilder    = new RelayBuilder();
        $this->container       = $container;
        $this->errorHandler    = $container->get(Handler\ErrorInterface::class);
        $this->notFoundHandler = $container->get(Handler\NotFoundInterface::class);
        $this->view            = $container->get(View\Manager::class);
        $this->debug           = $debug;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $request  = $this->decorateRequest($request);
        $response = $this->decorateResponse($response);
        $relay    = $this->relayBuilder->newInstance($this->middleware);
        $error    = $this->errorHandler;
        $notFound = $this->notFoundHandler;

        try {
            $response = $relay($request, $response);
        } catch (\Exception $ex) {
            $response = $error($request, $response, $ex);
        }

        if (!$response instanceof ResponseInterface) {
            throw new Exception\InvalidResponse;
        }

        $body = (string)$response->getBody();
        if (0 === strlen($body)) {
            $response = $notFound($request, $response);
        }

        return $next ? $next($request, $response) : $response;
    }

    /**
     * Routers are middleware and can be added to Tonis.
     *
     * e.g., $router = $app->router();
     *       $router->get(...)
     *
     *       $app->add($router);
     *
     * @return Router\Router
     */
    public function router()
    {
        return $this->container->get(Router\Router::class);
    }

    /**
     * @param PackageInterface $package
     */
    public function package(PackageInterface $package)
    {
        $package->register($this);
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function options($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function head($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
    }

    /**
     * {@inheritDoc}
     */
    public function any($path, $handler)
    {
        return $this->route($path, $handler, __FUNCTION__);
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
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Retrieves environment variables from $_ENV or getenv(). If the key
     * does not exist in $_ENV then getenv() is checked. Converts return value of
     * getenv() from false to null if there was an error.
     *
     * @param string $key
     * @param null   $value
     * @return mixed
     */
    public function env($key, $value = null)
    {
        if ($value !== null) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }

        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        return false === getenv($key) ? null : getenv($key);
    }

    /**
     * @param string   $path
     * @param callable $handler
     * @param string   $type
     */
    private function route($path, $handler, $type)
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
