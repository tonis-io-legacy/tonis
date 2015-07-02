<?php
namespace Tonis\Http;

use Tonis\App;
use Tonis\Http\Response as TonisResponse;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;

/**
 * @covers \Tonis\Http\Response
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @var Response */
    private $response;
    /** @var DiactorosResponse */
    private $dResponse;
    /** @var App */
    private $app;

    protected function setUp()
    {
        $this->app = new App();
        $this->dResponse = new DiactorosResponse;
        $this->response  = new Response($this->app, $this->dResponse);
    }

    public function testJson()
    {
        $response = $this->response->json(['foo' => 'bar']);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(json_encode(['foo' => 'bar']), $response->getBody()->__toString());
        $this->assertSame($response->getHeader('Content-Type'), ['application/json']);
    }

    public function testJsonp()
    {
        $response = $this->response->jsonp(['foo' => 'bar'], 'test');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('test(' . json_encode(['foo' => 'bar']) . ');', $response->getBody()->__toString());
        $this->assertSame($response->getHeader('Content-Type'), ['application/javascript']);
    }

    public function testRender()
    {
        $res = $this->response->render('error/404', ['request' => ServerRequestFactory::fromGlobals()]);
        $this->assertSame(200, $res->getStatusCode());
        $this->assertContains('Page Not Found', $res->getBody()->__toString());
    }

    /**
     * @dataProvider proxyProvider
     */
    public function testProxyCalls($method, $input = null)
    {
        $res  = new DiactorosResponse();
        $tres = new TonisResponse(new App, $res);

        $expected = call_user_func_array([$res, $method], (array) $input);
        $actual   = call_user_func_array([$tres, $method], (array) $input);

        $this->assertSame($expected, $actual);
    }


    /**
     * @dataProvider cloneProxyProvider
     */
    public function testProxyCloneCalls($method, $input)
    {
        $result = call_user_func_array([$this->response, $method], $input);

        $this->assertNotSame($this->response, $result);
        $this->assertInstanceOf(Response::class, $result);
    }

    public function testApp()
    {
        $this->assertSame($this->app, $this->response->app());
    }

    public function proxyProvider()
    {
        return [
            ['getProtocolVersion', ['1.0']],
            ['getHeaders', [[]]],
            ['hasHeader', ['Content-Type']],
            ['getHeader', ['Content-Type']],
            ['getHeaderLine', ['Content-Type']],
            ['getBody'],
            ['getReasonPhrase'],
        ];
    }

    public function cloneProxyProvider()
    {
        return [
            ['withProtocolVersion', ['1.0']],
            ['withBody', [new Stream(sys_get_temp_dir())]],
            ['withHeader', ['Content-Type', 'application/json']],
            ['withAddedHeader', ['Content-Type', 'application/json']],
            ['withoutHeader', ['Content-Type']],
            ['withStatus', [200]],
        ];
    }
}
