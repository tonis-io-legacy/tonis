<?php
namespace Tonis\Test;

use Tonis\Http\Request;
use Tonis\Http\Response;

/**
 * @covers \Tonis\Test\TonisPsr7TraitTest
 */
class TonisPsr7TraitTest extends \PHPUnit_Framework_TestCase
{
    use TonisPsr7Trait;

    public function testNewTonisResponse()
    {
        $this->assertInstanceOf(Response::class, $this->newTonisResponse());
    }

    public function testNewTonisRequest()
    {
        $request = $this->newTonisRequest('/foo', ['REQUEST_METHOD' => 'POST']);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('/foo', $request->getUri()->getPath());
        $this->assertSame('POST', $request->getMethod());
    }
}