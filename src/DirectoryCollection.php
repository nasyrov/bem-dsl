<?php namespace Lego\DSL;

use ArrayIterator;
use LogicException;

class DirectoryCollection implements DirectoryCollectionInterface
{
    /**
     * Collection of directories.
     * @var DirectoryInterface[]
     */
    protected $directories;

    public function add($path)
    {
        if (is_array($path)) {
            foreach ($path as $value) {
                $this->add($value);
            }

            return $this;
        } elseif (!is_dir($path)) {
            throw new LogicException(sprintf('The "%s" directory path does not exist.', $path));
        }

        $this->directories[] = $path;

        return $this;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->directories);
    }
}
