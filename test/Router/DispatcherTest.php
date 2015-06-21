<?php
namespace Tonis\Router;

use Tonis\TestAsset\NewRequestTrait;

/**
 * @covers \Tonis\Router\Dispatcher
 */
class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var Dispatcher */
    private $dispatcher;

    protected function setUp()
    {
        $router = new Router();
        $router->get('/foo', 'handler');

        $this->dispatcher = $router->getDispatcher();
    }

    public function testDispatchProxiesToFastRouter()
    {
        $result = $this->dispatcher->dispatch($this->newRequest('/foo'));
        $this->assertSame(1, $result[0]);
    }
}
