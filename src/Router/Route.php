<?php
namespace Tonis\Router;

final class Route
{
    /** @var string */
    private $path;
    /** @var mixed */
    private $handler;
    /** @var array */
    private $methods = [];
    /** @var string */
    private $regex;
    /** @var \SplFixedArray */
    protected $tokens;

    /**
     * @param string $path
     * @param string $handler
     */
    public function __construct($path, $handler)
    {
        $this->handler = $handler;
        $this->path = $path;
        $this->tokens = new \SplFixedArray();
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        $this->init();
        return $this->regex;
    }

    /**
     * @return null|\SplFixedArray
     */
    public function getTokens()
    {
        $this->init();
        return $this->tokens;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function methods(array $methods)
    {
        foreach ($methods as &$method) {
            $method = strtoupper($method);
        }
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    private function init()
    {
        if ($this->regex) {
            return;
        }
        $this->regex = $this->path;
        $matches = [];
        if ($count = preg_match_all('@{([^A-Za-z]*([A-Za-z]+))([?]?)(?::([^}]+))?}@', $this->regex, $matches)) {
            $this->tokens = new \SplFixedArray($count);
            foreach ($matches[1] as $index => $token) {
                $fullString = $matches[1][$index];
                $name = $matches[2][$index];
                $optional = !empty($matches[3][$index]);
                $constraint = empty($matches[4][$index]) ? '.*' : $matches[4][$index];
                if ($optional) {
                    $replace = sprintf('(?:%s(?<%s>%s))?', str_replace($name, '', $fullString), $name, $constraint);
                } else {
                    $replace = sprintf('(?<%s>%s)', $name, $constraint);
                }
                $this->regex = str_replace($matches[0][$index], $replace, $this->regex);
                $this->tokens[$index] = [$name, $optional];
            }
        }
    }
}
