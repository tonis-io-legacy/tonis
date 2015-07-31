<?php
namespace Tonis\Router\Resolver;

use StdClass;

/**
 * @covers \Tonis\Router\Resolver\Invoke
 */
class InvokeTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $r = new Invoke;
        $this->assertSame('foo', $r->resolve('foo'));
        $this->assertSame(false, $r->resolve(false));
        $this->assertInstanceOf(StdClass::class, $r->resolve('StdClass'));
    }
}
