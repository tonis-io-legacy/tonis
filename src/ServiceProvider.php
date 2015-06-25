<?php
namespace Tonis;

use League\Container\ServiceProvider as BaseServiceProvider;
use League\Plates\Engine;
use Tonis\View\PlatesStrategy;

class ServiceProvider extends BaseServiceProvider
{
    /** @var array */
    protected $provides = [
        View\Manager::class
    ];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $container = $this->getContainer();
        $container->add(View\Manager::class, function() {
            return new View\Manager(new PlatesStrategy(new Engine(__DIR__ . '/../view')));
        });
    }
}
