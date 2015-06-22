<?php
namespace Tonis\View;

use League\Plates\Engine;

/**
 * @covers \Tonis\View\PlatesStrategy
 */
class PlatesStrategyTest extends \PHPUnit_Framework_TestCase
{
    /** @var Engine */
    private $engine;
    /** @var PlatesStrategy */
    private $strategy;

    public function testGetEngine()
    {
        $this->assertSame($this->engine, $this->strategy->getEngine());
    }

    public function testRender()
    {
        $this->assertSame($this->engine->render('simple'), $this->strategy->render('simple'));
    }

    public function testCanRender()
    {
        $this->assertSame($this->engine->exists('does-not-exist'), $this->strategy->canRender('does-not-exist'));
        $this->assertSame($this->engine->exists('simple'), $this->strategy->canRender('simple'));
    }

    protected function setUp()
    {
        $this->engine = new Engine(__DIR__ . '/../_view');
        $this->strategy = new PlatesStrategy($this->engine);
    }
}
