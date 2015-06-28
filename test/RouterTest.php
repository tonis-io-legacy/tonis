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

        $that = $this;
        $this->router->get('/{foo}', function () use ($that) {
            return 'success';
        });

        $result = $this->router->__invoke($request, $response);
        $this->assertSame('success', $result);
    }

    /**
     * @dataProvider httpVerbProvider
     * @param string $method
     */
    public function testHttpVerbsProxy($method)
    {
        $this->markTestSkipped('need to fix');
        $handler = function() {};
        $collector = $this->router->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);

        $this->router->$method('/foo', $handler);
        $this->assertNotEmpty($collector->getData()[0]);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0][strtoupper($method)]);
    }

    public function testAny()
    {
        $this->markTestIncomplete('need to fix');
        $handler = function() {};

        $collector = $this->router->getRouteCollector();
        $this->assertEmpty($collector->getData()[0]);
        $this->router->any('/foo', $handler);

        $this->assertNotEmpty($collector->getData()[0]);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['GET']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['POST']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['PATCH']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['PUT']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['DELETE']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['HEAD']);
        $this->assertEquals(['/foo' => new Route($handler)], $collector->getData()[0]['OPTIONS']);
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
