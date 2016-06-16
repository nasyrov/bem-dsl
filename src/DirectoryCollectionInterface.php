<?php namespace Lego\DSL;

/**
 * Interface DirectoryCollectionInterface.
 * @package Lego\DSL
 */
interface DirectoryCollectionInterface
{
    /**
     * Adds new directory.
     *
     * @param string|array $path
     *
     * @return DirectoryCollectionInterface
     */
    public function add($path);
}
