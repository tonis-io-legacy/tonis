<?php
namespace Tonis;

use Interop\Container\ContainerInterface;
use Tonis\Http\Request as TonisRequest;
use Tonis\Http\Response as TonisResponse;
use Tonis\Router\Router;
use Tonis\Test\TonisPsr7Trait;
use Zend\Diactoros\Response;

/**
 * @covers \Tonis\App
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    use TonisPsr7Trait;

    /** @var App */
    private $app;

    protected function setUp()
    {
        $this->app = new App();
    }

    public function testIsDebug()
    {
        $this->assertFalse($this->app->isDebug());
        $app = new App(null, true);
        $this->assertTrue($app->isDebug());
    }

    public function testEnv()
    {
        $this->assertNull($this->app->env('doesnotexist'));
        $this->app->env('foo', 'bar');
        $this->assertSame('bar', $this->app->env('foo'));
        $this->assertSame('bar', getenv('foo'));
        $this->assertSame('bar', $_ENV['foo']);
    }

    public function testPackage()
    {
        $app     = $this->app;
        $package = new TestAsset\Package;

        $app->package($package);
        $app($this->newRequest('/'), new Response);

        $this->assertTrue($package->ran);
    }

    /**
     * @covers \Tonis\Exception\InvalidResponse
     * @expectedException \Tonis\Exception\InvalidResponse
     * @expectedExceptionMessage An invalid response was returned from handler
     */
    public function testInvalidResponseThrowsException()
    {
        $app = $this->app;
        $app->get('/', function () {});
        $app($this->newTonisRequest('/'), $this->newTonisResponse());
    }

    public function testAdd()
    {
        $router = $this->app->router();
        $router->get('/', function ($req, $res) {
            return $res->write('success');
        });

        $this->app->add($router);

        $response = $this->app->__invoke($this->newRequest('/'), new Response());
        $this->assertInstanceOf(TonisResponse::class, $response);
        $this->assertSame('success', $response->getBody()->__toString());
    }

    public function testExceptionsGetHandledByErrorHandler()
    {
        $handler = function ($request, $response) {
            throw new \RuntimeException('exception was caught');
        };

        $app = $this->app;
        $app->add($handler);

        $response = $app($this->newRequest('/'), new Response());
        $this->assertContains('exception was caught', $response->getBody()->__toString());
    }

    public function testRouter()
    {
        $router = $this->app->router();
        $this->assertInstanceOf(Router::class, $router);
        $this->assertNotSame($router, $this->app->router());
    }

    public function testGetContainer()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->app->getContainer());
    }

    public function testGetView()
    {
        $this->assertInstanceOf(View\Manager::class, $this->app->getView());
    }

    /**
     * @dataProvider httpVerbProvider
     */
    public function testHttpVerbs($method)
    {
        $this->app->$method('/foo', function ($req, $res) {
            return $res->write('success');
        });
        $request = $this->newRequest('/foo', ['REQUEST_METHOD' => strtoupper($method)]);
        $result = $this->app->__invoke($request, new Response());
        $this->assertSame('success', $result->getBody()->__toString());
    }

    public function testDecoration()
    {
        $handler =  function ($req, $res) {
            $this->assertInstanceOf(TonisRequest::class, $req);
            $this->assertInstanceOf(TonisResponse::class, $res);

            return $res->write('success');
        };

        $this->app->add($handler);

        $res = $this->app->__invoke($this->newRequest('/'), new Response());
        $this->assertInstanceOf(TonisResponse::class, $res);
        $this->assertSame('success', $res->getBody()->__toString());

        $res = $this->app->__invoke($this->newTonisRequest('/'), $this->newTonisResponse());
        $this->assertInstanceOf(TonisResponse::class, $res);
        $this->assertSame('success', $res->getBody()->__toString());
    }

    public function testDoneHandler()
    {
        $app      = $this->app;
        $response = $app($this->newRequest('/'), new Response, function ($request, $response) {
            return $response->write('success');
        });
        $this->assertContains('success', $response->getBody()->__toString());
    }

    public function testAny()
    {
        $app = $this->app;
        $ran = false;

        $app->any('/', function ($req, $res) use (&$ran) {
            $ran = !$ran;
            return $res;
        });

        $app($this->newTonisRequest('/'), $this->newTonisResponse());
        $this->assertTrue($ran);

        $app($this->newTonisRequest('/', ['REQUEST_METHOD' => 'POST']), $this->newTonisResponse());
        $this->assertFalse($ran);

        $app($this->newTonisRequest('/', ['REQUEST_METHOD' => 'HEAD']), $this->newTonisResponse());
        $this->assertTrue($ran);
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
