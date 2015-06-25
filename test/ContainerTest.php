<?php
namespace Tonis;

use stdClass;

/**
 * @covers \Tonis\Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Container */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    public function testHas()
    {
        $this->container->add(StdClass::class);

        $this->assertSame($this->container->has(StdClass::class), $this->container->isRegistered(StdClass::class));
    }
}
