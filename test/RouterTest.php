<?php
namespace Tonis;

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

    public function testParam()
    {
        $this->router->param('id', function ($req, $res, $next) {
            $res->write($req['id']);
            return $next($req, $res);
        });
        $this->router->get('/{id}', function ($req, $res) {
            return $res->write('get');
        });

        $request  = $this->newTonisRequest('/1234');
        $response = $this->newTonisResponse();
        $handler  = function($req, $res) {
            return $res;
        };

        $result = $this->router->__invoke($request, $response, $handler);
        $this->assertSame('1234get', $result->getBody()->__toString());
    }

    public function testAddingMiddleware()
    {
        $this->router->add(function ($req, $res) {
            return $res->write('add');
        });
        $this->router->get('/', function($req, $res) {
            return $res;
        });

        $request  = $this->newTonisRequest('/');
        $response = $this->newTonisResponse();
        $result   = $this->router->__invoke($request, $response, function() {});

        $this->assertSame('add', $result->getBody()->__toString());
    }

    public function testInvokeWithNoRoute()
    {
        $request  = $this->newTonisRequest('/');
        $response = $this->newTonisResponse();
        $result   = $this->router->__invoke($request, $response, function ($req, $res) {
            return $res->write('success');
        });

        $this->assertSame('success', $result->getBody()->__toString());
    }

    public function testInvokeWithRoute()
    {
        $request  = $this->newTonisRequest('/bar');
        $response = $this->newTonisResponse();

        $that = $this;
        $this->router->get('/{foo}', function ($req, $res) use ($that) {
            return $res->write('success');
        });

        $result = $this->router->__invoke($request, $response, function() {});
        $this->assertSame('success', $result->getBody()->__toString());
    }

    /**
     * @dataProvider httpVerbProvider
     * @param string $method
     */
    public function testHttpVerbsProxy($method)
    {
        $handler   = function () {
        };
        $refl      = new \ReflectionClass($this->router);
        $collector = $refl->getProperty('collector');
        $collector->setAccessible(true);
        $collector = $collector->getValue($this->router);

        $this->assertEmpty($collector->getData()[0]);

        $this->router->$method('/foo', $handler);
        $this->assertNotEmpty($collector->getData()[0]);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0][strtoupper($method)]);
    }

    public function testAny()
    {
        $handler   = function () {
        };
        $refl      = new \ReflectionClass($this->router);
        $collector = $refl->getProperty('collector');
        $collector->setAccessible(true);
        $collector = $collector->getValue($this->router);

        $this->assertEmpty($collector->getData()[0]);
        $this->router->any('/foo', $handler);

        $this->assertNotEmpty($collector->getData()[0]);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['GET']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['POST']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['PATCH']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['PUT']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['DELETE']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['HEAD']);
        $this->assertEquals(['/foo' => $handler], $collector->getData()[0]['OPTIONS']);
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
