<?php
namespace Tonis;

/**
 * @covers \Tonis\RouteMap
 */
class RouteMapTest extends \PHPUnit_Framework_TestCase
{
    /** @var RouteMap */
    private $map;

    protected function setUp()
    {
        $this->map = new RouteMap;
    }

    public function testAdd()
    {
        $handler = function () {};

        $this->assertCount(0, $this->map);
        $this->map->add('/foo', $handler);
        $this->assertCount(1, $this->map);

        $route = current($this->map->getRoutes());

        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame('/foo', $route->getPath());
        $this->assertSame($handler, $route->getHandler());
    }

    public function testAssmeble()
    {
        $handler = function () {};

        $this->map->add('/noname', $handler);
        $this->map->add('/basic', $handler)->name('basic');
        $this->map->add('/param/{id}', $handler)->name('param');
        $this->map->add('/optional/{id}[-{slug}]', $handler)->name('optional');

        $this->assertSame('/optional/1234-title', $this->map->assemble('optional', ['id' => 1234, 'slug' => 'title']));
        $this->assertSame('/optional/1234', $this->map->assemble('optional', ['id' => 1234]));
        $this->assertSame('/basic', $this->map->assemble('basic'));
        $this->assertSame('/param/1234', $this->map->assemble('param', ['id' => 1234]));
    }

    /**
     * @covers \Tonis\Exception\MissingRoute
     * @expectedException \Tonis\Exception\MissingRoute
     * @expectedExceptionMessage Route with name "foo" does not exist
     */
    public function testAssmebleThrowsExceptionForMissingRoute()
    {
        $this->map->assemble('foo');
    }

    public function testIteration()
    {
        $handler = function() {};
        $this->map->add('/foo', $handler);
        $this->map->add('/bar', $handler);
        $this->map->add('/baz', $handler);
        $this->map->add('/foobar', $handler);

        $i = 0;
        foreach ($this->map as $key => $route) {
            $this->assertSame($i++, $key);
            $this->assertInstanceOf(Route::class, $route);
        }
    }

    /**
     * @covers \Tonis\Exception\MissingRouteParam
     * @expectedException \Tonis\Exception\MissingRouteParam
     * @expectedExceptionMessage Route "foo" requires param "id" but it was missing
     */
    public function testAssembleWithMissingParamsThrowsException()
    {
        $this->map->add('/foo/{id}', function() {})->name('foo');
        $this->map->assemble('foo');
    }

    public function testHasRoute()
    {
        $this->assertFalse($this->map->hasRoute('foo'));
        $this->map->add('/foo', function () {})->name('foo');
        $this->assertTrue($this->map->hasRoute('foo'));
    }
}
