<?php
namespace Tonis\Handler;

use Tonis\TestAsset\NewRequestTrait;

/**
 * @covers \Tonis\Handler\ErrorHandler
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var ErrorHandler */
    private $handler;

    protected function setUp()
    {
        $this->handler = new ErrorHandler;
    }

    public function testStatusCodeResetOnInvalidExceptionCode()
    {
        $handler  = $this->handler;
        $request  = $this->newTonisRequest('/');
        $response = $this->newTonisResponse();
        $response = $handler($request, $response, new \RuntimeException('foo', 300));

        $this->assertSame(500, $response->getStatusCode());
    }

    public function testInvoke()
    {
        $handler  = $this->handler;
        $request  = $this->newTonisRequest('/');
        $response = $this->newTonisResponse();
        $response = $handler($request, $response, new \RuntimeException('foo error', 404));

        $this->assertSame(404, $response->getStatusCode());
        $this->assertContains('foo error', $response->getBody()->__toString());
    }
}
