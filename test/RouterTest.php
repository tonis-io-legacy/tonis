<?php
namespace Tonis;

use FastRoute\Dispatcher\GroupCountBased;
use Tonis\TestAsset\NewRequestTrait;
use Zend\Diactoros\Response;

/**
 * @covers \Tonis\Router
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var Router */
    private $router;

    protected function setUp()
    {
        $this->router = new Router;
    }

    public function testGetDispatcher()
    {
        $this->assertInstanceOf(GroupCountBased::class, $this->router->getDispatcher());
    }

    public function testInvokeWithNoRoute()
    {
        $request = $this->newRequest('/');
        $response = new Response();

        $result = $this->router->__invoke($request, $response, function() {
            return 'success';
        });

        $this->assertSame('success', $result);
    }

    public function testInvokeWithRoute()
    {
        $request = $this->newRequest('/bar');
        $response = new Response();

        $this->router->get('/{foo}', function ($req) {
            $this->assertSame('bar', $req->getAttribute('foo'));
            return 'success';
        });

        $result = $this->router->__invoke($request, $response);
        $this->assertSame('success', $result);
    }

    /**
     * @covers \Tonis\Exception\InvalidHandler
     * @expectedException \Tonis\Exception\InvalidHandler
     * @expectedExceptionMessage Invalid handler: must be callable
     */
    public function testInvalidHandlerThrowsException()
    {
        $this->router->get('/', false);
        $this->router->__invoke($this->newRequest('/'), new Response());
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
