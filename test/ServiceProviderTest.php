<?php
namespace Tonis;

use League\Container\Container;

/**
 * @covers \Tonis\ServiceProvider
 */
class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ServiceProvider */
    private $provider;

    protected function setUp()
    {
        $this->provider = new ServiceProvider();
    }

    public function testProvider()
    {
        $container = new Container();
        $container->addServiceProvider($this->provider);

        $this->provider->setContainer($container);
        $this->assertTrue($this->provider->provides(View\Manager::class));

        $this->provider->register();
        $this->assertTrue($container->isRegistered(View\Manager::class));

        $this->assertInstanceOf(Handler\ErrorInterface::class, $container->get(Handler\ErrorInterface::class));
        $this->assertInstanceOf(Handler\NotFoundInterface::class, $container->get(Handler\NotFoundInterface::class));
        $this->assertInstanceOf(Router::class, $container->get(Router::class));
        $this->assertInstanceOf(View\Manager::class, $container->get(View\Manager::class));
    }
}
