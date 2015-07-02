<?php
namespace Tonis\Http;

use Tonis\App;
use Tonis\Http\Request as TonisRequest;
use Tonis\TestAsset\NewRequestTrait;
use Zend\Diactoros\ServerRequest as DiactorosRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

/**
 * @covers \Tonis\Http\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    public function testArrayAccess()
    {
        $req = $this->newTonisRequest('/');
        $req['foo'] = 'bar';

        $this->assertCount(1, $req->getParams());
        $this->assertTrue(isset($req['foo']));
        $this->assertSame('bar', $req['foo']);

        unset($req['foo']);

        $this->assertCount(0, $req->getParams());
        $this->assertNull($req['foo']);
    }

    /**
     * @dataProvider proxyProvider
     */
    public function testProxyCalls($method, $input = null)
    {
        $req    = $this->newRequest('/');
        $treq   = new TonisRequest(new App, $req);

        $expected = call_user_func_array([$req, $method], (array) $input);
        $actual   = call_user_func_array([$treq, $method], (array) $input);

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider cloneProxyProvider
     */
    public function testProxyCloneCalls($method, $input = null)
    {
        $req    = $this->newRequest('/');
        $treq   = new TonisRequest(new App, $req);
        $actual = call_user_func_array([$treq, $method], (array) $input);

        $this->assertNotSame($req, $actual);
        $this->assertInstanceOf(Request::class, $actual);
    }

    public function testApp()
    {
        $app     = new App();
        $request = new DiactorosRequest();
        $req     = new Request($app, $request);

        $this->assertSame($app, $req->app());
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
            ['getRequestTarget'],
            ['getMethod'],
            ['getUri'],
            ['getServerParams'],
            ['getCookieParams'],
            ['getQueryParams'],
            ['getUploadedFiles'],
            ['getParsedBody'],
            ['getAttributes'],
            ['getAttribute', ['foo', 'bar']],
        ];
    }

    public function cloneProxyProvider()
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
            ['withUploadedFiles', [[]]],
        ];
    }
}
