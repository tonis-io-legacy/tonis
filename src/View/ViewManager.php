<?php
namespace Tonis\View;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ViewManager
{
    /** @var StrategyInterface[] */
    private $strategies = [];

    /**
     * @param string $template
     * @param array $params
     * @return null|string
     */
    public function render($template, array $params = [])
    {
        $result = null;
        foreach ($this->strategies as $strategy) {
            if ($strategy->canRender($template)) {
                $result = $strategy->render($template, $params);
            }

            if (null !== $result) {
                break;
            }
        }

        if (null === $result) {
            throw new Exception\UnableToRenderException;
        }

        return $result;
    }

    /**
     * @param string $name
     * @param StrategyInterface $strategy
     */
    public function addStrategy($name, StrategyInterface $strategy)
    {
        $this->strategies[$name] = $strategy;
    }

    /**
     * @param string $name
     * @return StrategyInterface
     * @throws MissingStrategyException
     */
    public function getStrategy($name)
    {
        if (!$this->hasStrategy($name)) {
            throw new MissingStrategyException;
        }
        return $this->strategies[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasStrategy($name)
    {
        return isset($this->strategies[$name]);
    }

    /**
     * @return StrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
}
