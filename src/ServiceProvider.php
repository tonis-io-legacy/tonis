<?php
namespace Tonis;

use League\Container\ServiceProvider as BaseServiceProvider;
use League\Plates\Engine;
use Tonis\Router\Resolver;

class ServiceProvider extends BaseServiceProvider
{
    /** @var array */
    protected $provides = [
        Engine::class,
        Handler\ErrorInterface::class,
        Handler\NotFoundInterface::class,
        View\Manager::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $container = $this->getContainer();

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
    }
}
