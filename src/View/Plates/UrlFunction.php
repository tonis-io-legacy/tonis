<?php
namespace Tonis\View\Plates;

use Tonis\Router\RouteMap;

class UrlFunction
{
    /** @var RouteMap */
    private $routeMap;

    /**
     * @param RouteMap $routeMap
     */
    public function __construct(RouteMap $routeMap)
    {
        $this->routeMap = $routeMap;
    }

    /**
     * Generates a url for the route specified if it exists.
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function __invoke($name, array $params = [])
    {
        return $this->routeMap->assemble($name, $params);
    }
}
