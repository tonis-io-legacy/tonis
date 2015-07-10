<?php
namespace Tonis\Router;

use Tonis\Http\Request;
use Tonis\TestAsset\NewRequestTrait;

/**
 * @cover \Tonis\Router\QueryParam
 */
class QueryParamTest extends \PHPUnit_Framework_TestCase
{
    use NewRequestTrait;

    /** @var QueryParam */
    private $param;

    protected function setUp()
    {
        $this->param = new QueryParam('test', function () {});
    }

    public function testShouldInvoke()
    {
        $request = $this
            ->newTonisRequest('/')
            ->withQueryParams(['test' => 'works']);
        $result = $this->param->shouldInvoke($request, $this->newTonisResponse());

        $this->assertTrue($result);
    }

    public function testGetValue()
    {
        $request = $this
            ->newTonisRequest('/')
            ->withQueryParams(['test' => 'works']);
        $result = $this->param->getValue($request, $this->newTonisResponse());

        $this->assertSame($result, 'works');
    }

    public function testArrayValues()
    {
        $param    = new QueryParam(['foo', 'bar'], function () { });
        $response = $this->newTonisResponse();

        $request = $this
            ->newTonisRequest('/')
            ->withQueryParams(['foo' => 'bar']);

        $this->assertFalse($param->shouldInvoke($request, $response));

        $request = $request->withQueryParams(['foo' => 'bar', 'bar' => 'baz']);
        $this->assertTrue($param->shouldInvoke($request, $response));

        $this->assertSame(['foo' => 'bar', 'bar' => 'baz'], $param->getValue($request, $response));
    }
}
