<?php
namespace Tonis;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Route
{
    /** @var callable */
    private $handler;
    /** @var array */
    private $params;

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
        $function = new \ReflectionFunction($this->handler);
        $args     = [$request, $response];

        foreach ($function->getParameters() as $index => $param) {
            if ($param->getPosition() < 2) {
                continue;
            }

            if (!$param->isOptional() && !array_key_exists($param->getName(), $this->params)) {
                throw new Exception\MissingArgumentException($param->getName());
            }

            $args[] = $this->params[$param->getName()];
        }

        return $function->invokeArgs($args);
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}
