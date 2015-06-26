<?php
namespace Tonis;

use Tonis\TestAsset\NewRequestTrait;
use Zend\Diactoros\Response;

/**
 * @covers \Tonis\Route
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var Route */
    private $route;

    protected function setUp()
    {
        $this->route = new Route(function() {
            return 'foo';
        });
    }

    public function testParams()
    {
        $params = ['foo' => 'bar'];
        $this->route->setParams($params);
        $this->assertSame($params, $this->route->getParams());
    }

    public function testInvoke()
    {
        $result = $this->route->__invoke($this->newRequest('/'), new Response());
        $this->assertSame('foo', $result);
    }

    public function testInvokeWithParams()
    {
        $that = $this;
        $route = new Route(function ($req, $res, $foo) use ($that) {
            $that->assertSame('bar', $req->getAttribute('foo'));
            $that->assertSame($req->getAttribute('foo'), $foo);

            return 'success';
        });
        $route->setParams(['foo' => 'bar']);

        $request = $this->newRequest('/');
        $result = $route->__invoke($request, new Response());

        $this->assertSame('success', $result);
    }

    /**
     * @covers \Tonis\Exception\MissingHandlerArgument
     * @expectedException \Tonis\Exception\MissingHandlerArgument
     * @expectedExceptionMessage Handler is missing argument "foo" but it is required
     */
    public function testInvokeWithMissingHandlerArgsThrowsException()
    {
        $route = new Route(function ($req, $res, $foo) {

        });
        $route->__invoke($this->newRequest('/'), new Response());
    }
}
