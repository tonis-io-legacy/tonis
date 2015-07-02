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
        RouteMap::class,
        Router::class,
        View\Manager::class
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $container = $this->getContainer();

        // route map which contains routes for lookup
        $container->singleton(RouteMap::class, function() {
            return new RouteMap;
        });

        // handles errors in the life-cycle
        $container->singleton(Handler\ErrorInterface::class, function() {
            return new Handler\ErrorHandler;
        });

        // handles not found errors in the life-cycle
        $container->singleton(Handler\NotFoundInterface::class, function() {
            return new Handler\NotFound;
        });

        // router
        $container->add(Router::class, function() use ($container) {
            return new Router($container->get(RouteMap::class));
        });

        // plates engine
        $container->singleton(Engine::class, function() use ($container) {
            $engine = new Engine(__DIR__ . '/../view');
            $engine->registerFunction('url', new View\Plates\UrlFunction($container->get(RouteMap::class)));

            return $engine;
        });

        // view manager
        $container->add(View\Manager::class, function () use ($container) {
            return new View\Manager(new View\PlatesStrategy($container->get(Engine::class)));
        });
    }
}
