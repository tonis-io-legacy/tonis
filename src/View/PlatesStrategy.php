<?php

namespace Tonis\View;

use League\Plates\Engine;

final class PlatesStrategy implements StrategyInterface
{
    /** @var Engine */
    private $engine;

    /**
     * @param Engine $plates
     */
    public function __construct(Engine $plates)
    {
        $this->engine = $plates;
    }

    /**
     * @return Engine
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

    /**
     * {@inheritDoc}
     */
    public function canRender($template)
    {
        return $this->engine->exists($template);
    }
}
