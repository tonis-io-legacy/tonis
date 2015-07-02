<?php
namespace Tonis\Http;

use Tonis\App;
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
    public function testProxyCalls($method, $input)
    {
        $req = $this->newTonisRequest('/');

        $result = call_user_func_array([$req, $method], $input);

        $this->assertNotSame($req, $result);
        $this->assertInstanceOf(Request::class, $result);
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
