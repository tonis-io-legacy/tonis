<?php

namespace Tonis\View;

final class TwigStrategy implements StrategyInterface
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
     * @return \Twig_Environment
     */
    public function getTwig()
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

    /**
     * {@inheritDoc}
     */
    public function canRender($template)
    {
        try {
            $this->twig->loadTemplate($template);
            return true;
        } catch (\Twig_Error_Loader $ex) {
            return false;
        }
    }
}
