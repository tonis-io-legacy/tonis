<?php
namespace Tonis\Http;

use Tonis\App;
use Zend\Diactoros\ServerRequest as DiactorosRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;
use Zend\Stratigility\Http\Request as StratigilityRequest;

/**
 * @covers \Tonis\Http\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider proxyProvider
     */
    public function testProxyCalls($method, $input)
    {
        $sRequest = new StratigilityRequest(new DiactorosRequest);
        $req = new Request(new App(), $sRequest);

        $result = call_user_func_array([$req, $method], $input);

        $this->assertNotSame($req, $result);
        $this->assertInstanceOf(Request::class, $result);
    }

    public function testApp()
    {
        $app = new App();
        $sRequest = new StratigilityRequest(new DiactorosRequest);
        $req = new Request($app, $sRequest);

        $this->assertSame($app, $req->app());
    }

    public function proxyProvider()
    {
        return [
            ['withRequestTarget', ['foo']],
            ['withProtocolVersion', ['1.0']],
            ['withBody', [new Stream(sys_get_temp_dir())]],
            ['withHeader', ['Content-Type', 'application/json']],
            ['withAddedHeader', ['Content-Type', 'application/json']],
            ['withoutHeader', ['Content-Type']],
            ['withMethod', ['GET']],
            ['withUri', [new Uri('www.foo.com')]],
            ['withCookieParams', [['foo' => 'bar']]],
            ['withQueryParams', [['foo' => 'bar']]],
            ['withParsedBody', [['foo' => 'bar']]],
            ['withAttribute', ['foo', 'bar']],
            ['withoutAttribute', ['foo']],
        ];
    }
}
