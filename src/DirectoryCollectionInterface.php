<?php namespace Lego\DSL;

/**
 * Interface DirectoryCollectionInterface.
 * @package Lego\DSL
 */
interface DirectoryCollectionInterface
{
    /**
     * Creates new DirectoryCollectionInterface instance.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine);

    /**
     * Adds new directory.
     *
     * @param string|array $path
     *
     * @return DirectoryCollectionInterface
     */
    public function add($path);
}
