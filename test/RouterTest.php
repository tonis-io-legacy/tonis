<?php
namespace Tonis;

/**
 * @covers \Tonis\Router\Router
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Router */
    private $router;

    protected function setUp()
    {
        $this->router = new Router;
    }

    public function testAddProxiesToRouteCollectorAddRoute()
    {
        $collector = $this->router->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);

        $this->router->add(['GET', 'POST'], 'foo', 'handler');
        $this->assertNotEmpty($collector->getData()[0]);
    }

    /**
     * @dataProvider httpVerbProvider
     * @param string $method
     */
    public function testHttpVerbsProxy($method)
    {
        $collector = $this->router->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);

        $this->router->$method('/foo', 'handler');
        $this->assertNotEmpty($collector->getData()[0]);
        $this->assertSame(['/foo' => 'handler'], $collector->getData()[0][strtoupper($method)]);
    }

    public function httpVerbProvider()
    {
        return [
            ['get'],
            ['post'],
            ['patch'],
            ['put'],
            ['delete'],
            ['head'],
            ['options']
        ];
    }
}
