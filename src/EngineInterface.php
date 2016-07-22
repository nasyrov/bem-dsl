<?php namespace BEM\DSL;

use Closure;

interface EngineInterface
{
    /**
     * @param array $directories
     *
     * @return EngineInterface
     */
    public function setDirectories(array $directories);

    /**
     * @param string $directory
     *
     * @return EngineInterface
     */
    public function setDirectory($directory);

    /**
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return mixed
     */
    public function match($expression, Closure $closure);

    /**
     * @param mixed $arr
     *
     * @return mixed
     */
    public function process($arr);

    /**
     * @param mixed $arr
     *
     * @return string
     */
    public function html($arr);

    /**
     * @param mixed $arr
     *
     * @return string
     */
    public function apply($arr);
}
