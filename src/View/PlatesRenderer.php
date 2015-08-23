<?php

namespace Tonis\View;

use League\Plates\Engine;

final class PlatesRenderer implements RendererInterface
{
    /** @var Engine */
    private $engine;

    /**
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * {@inheritDoc}
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, array $params = [])
    {
        return $this->engine->render($template, $params);
    }
}
