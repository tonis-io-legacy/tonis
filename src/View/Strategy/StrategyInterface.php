<?php

namespace Tonis\View\Strategy;

interface StrategyInterface
{
    /**
     * @param string $template
     * @return bool
     */
    public function canRender($template);

    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, array $params = []);
}
