<?php
namespace Tonis\Router;

use Tonis\Http\Request;
use Tonis\Http\Response;

abstract class AbstractParam
{
    /** @var string */
    protected $param;
    /** @var callable */
    protected $handler;

    /**
     * @param string $param
     * @param callable $handler
     */
    final public function __construct($param, callable $handler)
    {
        $this->param   = $param;
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $params = $request->getQueryParams();

        if (isset($params[$this->param])) {
            $handler = $this->handler;
            $handler($request, $response, $params[$this->param]);
        }

        return $next($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    abstract public function shouldInvoke(Request $request, Response $response);

    /**
     * @return string
     */
    final public function getParam()
    {
        return $this->param;
    }

    /**
     * @return callable
     */
    final public function getHandler()
    {
        return $this->handler;
    }
}
