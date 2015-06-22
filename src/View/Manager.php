<?php
namespace Tonis\View;

final class Manager
{
    /** @var StrategyInterface[] */
    private $strategies = [];
    /** @var StrategyInterface */
    private $fallbackStrategy;

    /**
     * @param StrategyInterface $fallbackStrategy
     */
    public function __construct(StrategyInterface $fallbackStrategy)
    {
        $this->fallbackStrategy = $fallbackStrategy;
    }

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
            $result = $this->fallbackStrategy->render($template, $params);
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
     * @throws Exception\MissingStrategy
     */
    public function getStrategy($name)
    {
        if (!$this->hasStrategy($name)) {
            throw new Exception\MissingStrategy($name);
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
     * @return StrategyInterface
     */
    public function getFallbackStrategy()
    {
        return $this->fallbackStrategy;
    }

    /**
     * @return StrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
}
