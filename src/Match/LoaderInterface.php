<?php namespace BEM\DSL\Match;

interface LoaderInterface
{
    /**
     * @param array $directories
     *
     * @return LoaderInterface
     */
    public function setDirectories(array $directories);

    /**
     * @param string|array $directory
     *
     * @return LoaderInterface
     */
    public function setDirectory($directory);
}
