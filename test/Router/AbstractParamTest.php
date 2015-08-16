<?php
namespace Tonis\Router;

use Tonis\Test\TonisPsr7Trait;

/**
 * @cover \Tonis\Router\AbstractParam
 */
class AbstractParamTest extends \PHPUnit_Framework_TestCase
{
    use TonisPsr7Trait;

    /** @var RouteParam */
    private $param;

    protected function setUp()
    {
        $this->param = new RouteParam('test', function ($req, $res, $value) {
            $req['callback'] = true;
        });
    }

    public function testInvoke()
    {
        $response = $this->newTonisResponse();
        $request  = $this->newTonisRequest('/');
        $request['test'] = 'works';

        $param = $this->param;
        $param($request, $response, function() {});

        $this->assertTrue($request['callback']);
    }

    public function testGetParam()
    {
        $this->assertSame('test', $this->param->getParam());
    }

    public function testGetHandler()
    {
        $this->assertInternalType('callable', $this->param->getHandler());
    }
}
