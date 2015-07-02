<?php
namespace Tonis;

/**
 * @covers \Tonis\Route
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /** @var Route */
    private $route;

    protected function setUp()
    {
        $this->route = new Route('/foo', function () {});
    }

    public function testName()
    {
        $this->assertNull($this->route->name());
        $this->assertSame('foo', $this->route->name('foo'));
        $this->assertSame('foo', $this->route->name());
    }

    public function testGetPath()
    {
        $this->assertSame('/foo', $this->route->getPath());
    }

    public function testGetHandler()
    {
        $this->assertEquals(function () {}, $this->route->getHandler());
    }
}
