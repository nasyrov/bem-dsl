<?php namespace Lego\DSL\Matcher;

use Iterator;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class MatcherLoader
{
    protected $directories = [];

    public function load($directory)
    {
        if (is_array($directory)) {
            foreach ($directory as $value) {
                $this->load($value);
            }
        } elseif (in_array($directory, $this->directories)) {
            throw new LogicException(sprintf('The "%s" directory is already registred.', $directory));
        } else {
            $this->find($directory);

            $this->directories[] = $directory;
        }

        return $this;
    }

    protected function find($directory)
    {
        if (!is_dir($directory)) {
            throw new LogicException(sprintf('The "%s" directory does not exist.', $directory));
        }

        $directoryIterator = new RecursiveDirectoryIterator($directory);
        $iteratorIterator  = new RecursiveIteratorIterator($directoryIterator);
        $regexIterator     = new RegexIterator($iteratorIterator, '/^.+\.php$/i');

        $this->register($regexIterator);
    }

    protected function register(Iterator $files)
    {
        foreach ($files as $file) {
            require_once $file->getPathName();
        }
    }
}
