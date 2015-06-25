<?php
namespace Tonis;

use FastRoute\Dispatcher\GroupCountBased;
use Interop\Container\ContainerInterface;
use Tonis\Http\Request as TonisRequest;
use Tonis\Http\Response as TonisResponse;
use Tonis\TestAsset\NewRequestTrait;
use Zend\Diactoros\Response;

/**
 * @covers \Tonis\App
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var App */
    private $app;

    protected function setUp()
    {
        $this->app = new App();
    }

    public function testAdd()
    {
        $router = $this->app->router();
        $router->get('/', function($req, $res) {
            $res->end('success');
        });

        $this->app->add($router);

        $response = $this->app->__invoke($this->newRequest('/'), new Response());
        $this->assertInstanceOf(TonisResponse::class, $response);
        $this->assertSame('success', $response->getBody()->__toString());
    }

    public function testConfig()
    {
        $this->assertSame('development', $this->app->config('env'));
        $this->assertSame('production', $this->app->config('env', 'production'));
        $this->assertSame('production', $this->app->config('env'));
        $this->assertNull($this->app->config('does-not-exist'));
    }

    public function testRouter()
    {
        $router = $this->app->router();
        $this->assertInstanceOf(Router::class, $router);
        $this->assertNotSame($router, $this->app->router());
    }

    public function testGetServiceContainer()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->app->getServiceContainer());
    }

    public function testGetViewManager()
    {
        $this->assertInstanceOf(View\Manager::class, $this->app->getViewManager());
    }

    /**
     * @dataProvider httpVerbProvider
     */
    public function testHttpVerbs($method)
    {
        $this->app->$method('/foo', function ($req, $res) {
            $res->end('success');
        });
        $request = $this->newRequest('/foo', ['REQUEST_METHOD' => strtoupper($method)]);
        $result = $this->app->__invoke($request, new Response());
        $this->assertSame('success', $result->getBody()->__toString());
    }

    public function testDecoration()
    {
        $result = $this->app->__invoke($this->newRequest('/'), new Response(), function ($req, $res) {
            $this->assertInstanceOf(TonisRequest::class, $req);
            $this->assertInstanceOf(TonisResponse::class, $res);

            $res->write('success');
        });
        $this->assertSame('success', $result->getBody()->__toString());

        $result = $this->app->__invoke($this->newTonisRequest('/'), $this->newTonisResponse(), function ($req, $res) {
            $this->assertInstanceOf(TonisRequest::class, $req);
            $this->assertInstanceOf(TonisResponse::class, $res);

            $res->write('success');
        });
        $this->assertSame('success', $result->getBody()->__toString());
    }

    public function httpVerbProvider()
    {
        return [
            ['get'],
            ['post'],
            ['patch'],
            ['delete'],
            ['put'],
            ['head'],
            ['options'],
        ];
    }
}
