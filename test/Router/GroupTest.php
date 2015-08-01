<?php
namespace Tonis\Router;

/**
 * @covers \Tonis\Router\Group
 */
class GroupedTest extends \PHPUnit_Framework_TestCase
{
    /** @var RouterInterface */
    private $router;
    /** @var Group */
    private $group;

    protected function setUp()
    {
        $this->router = new Router();
        $this->group  = new Group($this->router, 'prefix');
    }

    /**
     * @dataProvider proxyProvider
     */
    public function testProxyCalls($method)
    {
        $handler = function() {};
        $this->group->$method('/foo', $handler);

        $refl = new \ReflectionClass($this->router);
        $col  = $refl->getProperty('collector');
        $col->setAccessible(true);

        $col = $col->getValue($this->router);
        /** @var \FastRoute\RouteCollector $col */
        $data = $col->getData();
        $this->assertArrayHasKey(strtoupper($method), $data[0]);
    }

    public function testAny()
    {
        $handler = function() {};
        $this->group->any('/foo', $handler);

        $refl = new \ReflectionClass($this->router);
        $col  = $refl->getProperty('collector');
        $col->setAccessible(true);

        $col = $col->getValue($this->router);
        /** @var \FastRoute\RouteCollector $col */
        $data = $col->getData();
        $this->assertArrayHasKey(strtoupper('GET'), $data[0]);
        $this->assertArrayHasKey(strtoupper('POST'), $data[0]);
    }

    public function testGetRouter()
    {
        $this->assertSame($this->router, $this->group->getRouter());
    }

    public function testGroup()
    {
        $valid   = false;
        $handler = function (Group $group) use (&$valid) {
            $this->assertSame('prefix/foo', $group->getPrefix());
            $valid = true;
        };
        $this->group->group('/foo', $handler);
        $this->assertTrue($valid);
    }

    public function proxyProvider()
    {
        return [['get'], ['post'], ['put'], ['patch'], ['delete'], ['head'], ['options']];
    }
}
