<?php
namespace Tonis;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionFunction;

class Route
{
    /** @var callable */
    private $handler;
    /** @var array */
    private $params = [];

    /**
     * @param callable $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        foreach ($this->params as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $function = new \ReflectionFunction($this->handler);
        $args     = $this->getFunctionArguments($function, $request, $response);

        return $function->invokeArgs($args);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Gets a list of function arguments for the handler. If a non-optional argument is missing
     * from the route an exception is thrown.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return array
     */
    private function getFunctionArguments(
        ReflectionFunction $function,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $args = ['request' => $request, 'response' => $response];

        foreach ($function->getParameters() as $index => $param) {
            if ($param->getPosition() < 2) {
                continue;
            }

            if (!$param->isOptional() && !array_key_exists($param->getName(), $this->params)) {
                throw new Exception\MissingHandlerArgument($param->getName());
            }

            $args[$param->getName()] = $this->params[$param->getName()];
        }

        return $args;
    }
}
