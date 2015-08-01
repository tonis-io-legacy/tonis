<?php
namespace Tonis\Router;

interface RouterInterface
{
    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function get($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function post($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function patch($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function put($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function delete($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function head($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function options($path, $handler);

    /**
     * @param string   $path
     * @param callable $handler
     * @return Route
     */
    public function any($path, $handler);
}
