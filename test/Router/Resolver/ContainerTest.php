<?php
namespace Tonis\Router\Resolver;

use Tonis\Container as LeagueContainer;
use StdClass;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $c = new LeagueContainer;
        $r = new Container($c);
        $this->assertSame(false, $r->resolve(false));
        $this->assertInstanceOf(StdClass::class, $r->resolve('StdClass'));
    }
}
