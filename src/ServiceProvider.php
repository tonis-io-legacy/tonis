<?php
namespace Tonis;

use League\Container\ServiceProvider as BaseServiceProvider;
use League\Plates\Engine;

class ServiceProvider extends BaseServiceProvider
{
    /** @var array */
    protected $provides = [
        Engine::class,
        Handler\ErrorInterface::class,
        Handler\NotFoundInterface::class,
        Router\RouteMap::class,
        Router\Router::class,
        View\Manager::class
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $container = $this->getContainer();

        // route map which contains routes for lookup
        $container->singleton(Router\RouteMap::class, function () {
            return new Router\RouteMap;
        });

        // handles errors in the life-cycle
        $container->singleton(Handler\ErrorInterface::class, function () {
            return new Handler\Error;
        });

        // handles not found errors in the life-cycle
        $container->singleton(Handler\NotFoundInterface::class, function () {
            return new Handler\NotFound;
        });

        // plates engine
        $container->singleton(Engine::class, function () use ($container) {
            $engine = new Engine(__DIR__ . '/../view');
            $engine->registerFunction('url', new View\Plates\UrlFunction($container->get(Router\RouteMap::class)));

            return $engine;
        });

        // view manager
        $container->singleton(View\Manager::class, function () use ($container) {
            return new View\Manager(new View\PlatesStrategy($container->get(Engine::class)));
        });

        // router
        $container->add(Router\Router::class, function () use ($container) {
            return new Router\Router($container->get(Router\RouteMap::class));
        });
    }
}
