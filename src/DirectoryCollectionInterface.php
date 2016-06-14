<?php namespace Lego\DSL;

use IteratorAggregate;

/**
 * Interface DirectoryCollectionInterface.
 * @package Lego\DSL
 */
interface DirectoryCollectionInterface extends IteratorAggregate
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
