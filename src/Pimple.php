<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use League\Plates\Engine;
use Pimple\Container;
use Tonis\View\Plates\UrlFunction;

class Pimple extends Container implements ContainerInterface
{
    public function __construct()
    {
        parent::__construct();

        // route map which contains routes for lookup
        $this[RouteMap::class] = function () {
            return new RouteMap;
        };

        // handles errors in the life-cycle
        $this[Handler\ErrorInterface::class] = function () {
            return new Handler\Error;
        };

        // handles not found errors in the life-cycle
        $this[Handler\NotFoundInterface::class] = function () {
            return new Handler\NotFound;
        };

        // plates engine
        $this[Engine::class] = function () {
            $engine = new Engine(__DIR__ . '/../view');
            $engine->registerFunction('url', new UrlFunction($this[RouteMap::class]));

            return $engine;
        };

        // view manager
        $this[View\Manager::class] = function () {
            return new View\Manager(new View\PlatesStrategy($this[Engine::class]));
        };

        // router (new instance every call)
        $this[Router::class] = $this->factory(function () {
            return new Router($this[RouteMap::class]);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        return $this[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
