<?php
namespace Tonis\View;

final class Manager
{
    /** @var Strategy\StrategyInterface[] */
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
     * @param Strategy\StrategyInterface $strategy
     */
    public function addStrategy($name, Strategy\StrategyInterface $strategy)
    {
        $this->strategies[$name] = $strategy;
    }

    /**
     * @param string $name
     * @return Strategy\StrategyInterface
     * @throws Exception\MissingStrategyException
     */
    public function getStrategy($name)
    {
        if (!$this->hasStrategy($name)) {
            throw new Exception\MissingStrategyException;
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
     * @return Strategy\StrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
}
