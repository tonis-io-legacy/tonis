<?php
namespace Tonis;

use FastRoute\Dispatcher\GroupCountBased;
use Tonis\TestAsset\NewRequestTrait;

/**
 * @covers \Tonis\FinalHandler
 */
class FinalHandlerTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var FinalHandler */
    private $finalHandler;

    protected function setUp()
    {
        $this->finalHandler = new FinalHandler();
    }

    public function testInvokeNotFound()
    {
        $response = $this->finalHandler->__invoke($this->newTonisRequest('/foo'), $this->newTonisResponse());
        $body = $response->getBody()->__toString();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertContains('404', $body);
        $this->assertContains('/foo', $body);
    }

    public function testInvokeWithException()
    {
        $response = $this->finalHandler->__invoke(
            $this->newTonisRequest('/foo'),
            $this->newTonisResponse(),
            new \RuntimeException('Message')
        );
        $body = $response->getBody()->__toString();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertContains('Message', $body);
    }

    public function testInvokeWithExceptionCode()
    {
        $response = $this->finalHandler->__invoke(
            $this->newTonisRequest('/foo'),
            $this->newTonisResponse(),
            new \RuntimeException('Message', 505)
        );
        $body = $response->getBody()->__toString();

        $this->assertSame(505, $response->getStatusCode());
        $this->assertContains('Message', $body);
    }

    public function testInvokeWithError()
    {
        $response = $this->finalHandler->__invoke(
            $this->newTonisRequest('/foo'),
            $this->newTonisResponse(),
            'Oops, something broke'
        );
        $body = $response->getBody()->__toString();

        $this->assertSame(500, $response->getStatusCode());
        $this->assertContains('Oops, something broke', $body);
    }
}
