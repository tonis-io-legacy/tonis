<?php
namespace Tonis\Http;

use Tonis\App;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;
use Zend\Stratigility\Http\Response as StratigilityResponse;

/**
 * @covers \Tonis\Http\Response
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @var Response */
    private $response;
    /** @var StratigilityResponse */
    private $sResponse;
    /** @var DiactorosResponse */
    private $dResponse;
    /** @var App */
    private $app;

    protected function setUp()
    {
        $this->app = new App();
        $this->dResponse = new DiactorosResponse;
        $this->sResponse = new StratigilityResponse($this->dResponse);
        $this->response = new Response($this->app, $this->sResponse);
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
    public function testProxyCalls($method, $input)
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
            ['withProtocolVersion', ['1.0']],
            ['withBody', [new Stream(sys_get_temp_dir())]],
            ['withHeader', ['Content-Type', 'application/json']],
            ['withAddedHeader', ['Content-Type', 'application/json']],
            ['withoutHeader', ['Content-Type']],
            ['withStatus', [200]],
        ];
    }
}
