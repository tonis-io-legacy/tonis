<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Stratigility\MiddlewarePipe;

final class App
{
    /** @var ContainerInterface */
    private $container;
    /** @var Router\Router */
    private $router;
    /** @var MiddlewarePipe */
    private $pipeline;
    /** @var bool */
    private $isRouterAdded = false;

    /**
     * @param ContainerInterface        $container
     * @param Response\EmitterInterface $emitter
     */
    public function __construct(
        ContainerInterface $container = null,
        Response\EmitterInterface $emitter = null
    ) {
        $this->container = $container;
        $this->emitter   = $emitter ?: new Response\SapiEmitter();
        $this->router    = new Router\Router(new Resolver\Basic($this->container));
        $this->pipeline  = new MiddlewarePipe();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $done
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $done = null)
    {
        $request  = $this->decorateRequest($request);
        $response = $this->decorateResponse($response);

        return $this->pipeline->__invoke($request, $response, $done);
    }

    /**
     * @param ServerRequestInterface|null $request
     * @param ResponseInterface|null      $response
     */
    public function run(ServerRequestInterface $request = null, ResponseInterface $response = null)
    {
        $request  = $request ?: ServerRequestFactory::fromGlobals();
        $response = $response ?: new Response();

        $response = $this($request, $response);

        $this->emitter->emit($response);
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function options($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function head($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * {@inheritDoc}
     */
    public function any($path, $handlers)
    {
        return $this->route($path, __FUNCTION__, array_slice(func_get_args(), 1));
    }

    /**
     * @param string|callable $pathOrHandler
     * @param callable        $handler
     */
    public function add($pathOrHandler, callable $handler = null)
    {
        $this->pipeline->pipe($pathOrHandler, $handler);
    }

    /**
     * Adds routing middleware to the pipeline. This happens automatically
     * when a route is added or when the application is invoked but can be
     * done manually if required.
     */
    public function addRouterMiddleware()
    {
        if ($this->isRouterAdded) {
            return;
        }

        $this->add($this->router);
        $this->isRouterAdded = true;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if (null === $this->container) {
            throw new Exception\NoContainerRegistered();
        }

        return $this->container;
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
     * @param string $path
     * @param string $type
     * @param mixed  $handlers
     * @return
     */
    private function route($path, $type, $handlers)
    {
        $route = $this->router->$type($path, $handlers);
        $this->addRouterMiddleware();

        return $route;
    }

    /**
     * Decorates the request as a Tonis\Http\Request.
     *
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface|Http\Request
     */
    private function decorateRequest(ServerRequestInterface $request)
    {
        if ($request instanceof Http\Request) {
            return $request;
        }
        return new Http\Request($request);
    }

    /**
     * Decorates the response as a Tonis\Http\Response.
     *
     * @param ResponseInterface $response
     * @return ResponseInterface|Http\Response
     */
    private function decorateResponse(ResponseInterface $response)
    {
        if ($response instanceof Http\Response) {
            return $response;
        }
        return new Http\Response($response);
    }
}
