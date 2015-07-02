<?php
namespace Tonis;

/**
 * @covers \Tonis\Route
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /** @var callable */
    private $handler;
    /** @var Route */
    private $route;

    protected function setUp()
    {
        $this->handler = function () {};
        $this->route   = new Route('/foo', $this->handler);
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
        $this->assertSame($this->handler, $this->route->getHandler());
    }
}
