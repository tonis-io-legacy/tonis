<?php
namespace Tonis\Handler;

use Tonis\Test\TonisPsr7Trait;

/**
 * @covers \Tonis\Handler\NotFound
 */
class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    use TonisPsr7Trait;

    /** @var NotFound */
    private $handler;

    protected function setUp()
    {
        $this->handler = new NotFound;
    }

    public function testInvoke()
    {
        $request  = $this->newTonisRequest('/');
        $response = $this->newTonisResponse();
        $handler  = $this->handler;
        $response = $handler($request, $response);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertContains('404', $response->getBody()->__toString());
    }
}
