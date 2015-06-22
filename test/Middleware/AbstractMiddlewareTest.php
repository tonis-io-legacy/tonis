<?php
namespace Tonis\Middleware;

use Tonis\App;
use Tonis\Http\Request as TonisRequest;
use Tonis\Http\Response as TonisResponse;
use Tonis\TestAsset\NewRequestTrait;
use Tonis\TestAsset\TestMiddleware;
use Zend\Diactoros\Response;
use Zend\Stratigility\Http\Request as StratigilityRequest;
use Zend\Stratigility\Http\Response as StratigilityResponse;

/**
 * @covers \Tonis\Middleware\AbstractMiddleware
 */
class AbstractMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    public function testInvoke()
    {
        $app = new App;

        $request = new TonisRequest($app, new StratigilityRequest($this->newRequest('/')));
        $response = new TonisResponse($app, new StratigilityResponse(new Response()));
        $next = function() {
            return 'ran';
        };

        $middle = $this->getMockForAbstractClass(AbstractMiddleware::class);
        $result = $middle->__invoke($request, $response, $next);
        $this->assertSame('ran', $result);

        $this->assertInstanceOf(TonisResponse::class, $middle->__invoke($request, $response));
    }

    public function testInvokeWithRouterResponse()
    {
        $app = new App;

        $request = new TonisRequest($app, new StratigilityRequest($this->newRequest('/')));
        $response = new TonisResponse($app, new StratigilityResponse(new Response()));

        $middle = new TestMiddleware();
        $result = $middle->__invoke($request, $response);
        $this->assertSame('foo', $result);
    }
}
