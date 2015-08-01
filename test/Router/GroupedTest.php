<?php
namespace Tonis\Router;

/**
 * @covers \Tonis\Router\Grouped
 */
class GroupedTest extends \PHPUnit_Framework_TestCase
{
    /** @var RouterInterface */
    private $router;
    /** @var Grouped */
    private $routeGroup;

    protected function setUp()
    {
        $this->router     = new Router();
        $this->routeGroup = new Grouped($this->router, 'prefix');
    }

    /**
     * @dataProvider proxyProvider
     */
    public function testProxyCalls($method)
    {
        $handler = function() {};
        $this->routeGroup->$method('/foo', $handler);

        $refl = new \ReflectionClass($this->router);
        $col  = $refl->getProperty('collector');
        $col->setAccessible(true);

        $col = $col->getValue($this->router);
        /** @var \FastRoute\RouteCollector $col */
        $data = $col->getData();
        $this->assertArrayHasKey(strtoupper($method), $data[0]);
    }

    public function testAny()
    {
        $handler = function() {};
        $this->routeGroup->any('/foo', $handler);

        $refl = new \ReflectionClass($this->router);
        $col  = $refl->getProperty('collector');
        $col->setAccessible(true);

        $col = $col->getValue($this->router);
        /** @var \FastRoute\RouteCollector $col */
        $data = $col->getData();
        $this->assertArrayHasKey(strtoupper('GET'), $data[0]);
        $this->assertArrayHasKey(strtoupper('POST'), $data[0]);
    }

    public function testGetRouter()
    {
        $this->assertSame($this->router, $this->routeGroup->getRouter());
    }

    public function testGroup()
    {
        $valid   = false;
        $handler = function (Grouped $group) use (&$valid) {
            $this->assertSame('prefix/foo', $group->getPrefix());
            $valid = true;
        };
        $this->routeGroup->group('/foo', $handler);
        $this->assertTrue($valid);
    }

    public function proxyProvider()
    {
        return [['get'], ['post'], ['put'], ['patch'], ['delete'], ['head'], ['options']];
    }
}
