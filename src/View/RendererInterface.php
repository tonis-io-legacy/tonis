<?php

namespace Tonis\View;

interface RendererInterface
{
    /**
     * Returns the underlying Templating engine for the renderer.
     *
     * @return mixed
     */
    public function getEngine();

    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, array $params = []);
}
