<?php
namespace Tonis\View;

use League\Plates\Engine;
use Tonis\TestAsset\ViewStrategy;

/**
 * @covers \Tonis\View\Manager
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ViewStrategy */
    private $fallback;
    /** @var Manager */
    private $manager;

    protected function setUp()
    {
        $this->fallback = new ViewStrategy;
        $this->manager = new Manager($this->fallback);
    }

    public function testRenderUsesFallback()
    {
        $this->assertSame($this->fallback->render('test', []), $this->manager->render('test', []));
    }

    public function testRenderWithStrategy()
    {
        $this->manager->addStrategy('plates', new PlatesStrategy(new Engine(__DIR__ . '/../_view')));
        $this->assertSame('It works!', trim($this->manager->render('simple')));

        $this->assertSame('does-not-exist:[]', $this->manager->render('does-not-exist'));
    }

    /**
     * @expectedException \Tonis\View\Exception\MissingStrategy
     * @expectedExceptionMessage No strategy with name "foo" has been registered
     * @covers \Tonis\View\Exception\MissingStrategy
     */
    public function testGetStrategyThrowsExceptionForMissingStrategy()
    {
        $this->manager->getStrategy('foo');
    }

    public function testGetStrategy()
    {
        $strategy = new ViewStrategy();
        $this->manager->addStrategy('foo', $strategy);
        $this->assertSame($strategy, $this->manager->getStrategy('foo'));
    }

    public function testGetStrategies()
    {
        $this->assertEmpty($this->manager->getStrategies());
        $this->manager->addStrategy('foo', new ViewStrategy());
        $this->assertCount(1, $this->manager->getStrategies());
    }

    public function testGetFallbackStrategy()
    {
        $this->assertSame($this->fallback, $this->manager->getFallbackStrategy());
    }
}
