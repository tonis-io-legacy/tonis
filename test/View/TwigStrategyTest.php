<?php
namespace Tonis\View;

/**
 * @covers \Tonis\View\TwigStrategy
 */
class TwigStrategyTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Twig_Environment */
    private $twig;
    /** @var TwigStrategy */
    private $strategy;

    public function testGetTwig()
    {
        $this->assertSame($this->twig, $this->strategy->getTwig());
    }

    public function testRender()
    {
        $this->assertSame($this->twig->render('simple.twig'), $this->strategy->render('simple.twig'));
    }

    public function testCanRender()
    {
        $this->assertSame(false, $this->strategy->canRender('does-not-exist'));
        $this->assertSame(true, $this->strategy->canRender('simple.twig'));
    }

    protected function setUp()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../_view'));
        $this->strategy = new TwigStrategy($this->twig);
    }
}
