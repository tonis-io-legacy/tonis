<?php
namespace Tonis\TestAsset;

use Tonis\View\StrategyInterface;

class ViewStrategy implements StrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function canRender($template)
    {
        return true;
    }
    /**
     * {@inheritDoc}
     */
    public function render($template, array $params = [])
    {
        $vars = [];
        foreach ($params as $key => $variable) {
            if ($variable instanceof \Exception) {
                $variable = get_class($variable);
            }
            $vars[$key] = $variable;
        }
        return $template . ':' . json_encode($vars);
    }
}
