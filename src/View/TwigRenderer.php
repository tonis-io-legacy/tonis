<?php

namespace Tonis\View;

final class TwigRenderer implements RendererInterface
{
    /** @var \Twig_Environment */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritDoc}
     */
    public function getEngine()
    {
        return $this->twig;
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, array $params = [])
    {
        return $this->twig->render($template, $params);
    }
}
