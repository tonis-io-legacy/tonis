<?php
namespace Tonis\Router;

/**
 * @covers \Tonis\Router\RouteCollection
 */
class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var RouteCollection */
    private $collection;

    protected function setUp()
    {
        $this->collection = new RouteCollection;
    }

    public function testGetDispatcher()
    {
        $dispatcher = $this->collection->getDispatcher();
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
    }

    public function testAddProxiesToRouteCollectorAddRoute()
    {
        $collector = $this->collection->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);

        $this->collection->add(['GET', 'POST'], 'foo', 'handler');
        $this->assertNotEmpty($collector->getData()[0]);
    }

    /**
     * @dataProvider httpVerbProvider
     * @param string $method
     */
    public function testHttpVerbsProxy($method)
    {
        $collector = $this->collection->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);

        $this->collection->$method('/foo', 'handler');
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
