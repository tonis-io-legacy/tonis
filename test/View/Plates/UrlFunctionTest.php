<?php
namespace Tonis\View\Plates;

use Tonis\RouteMap;

/**
 * @covers \Tonis\View\Plates\UrlFunction
 */
class UrlFunctionTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $map = new RouteMap();
        $map
            ->add('/foo/{id}', function () {})
            ->name('foo');

        $url = new UrlFunction($map);

        $this->assertSame('/foo/1234', $url('foo', ['id' => 1234]));
    }
}
