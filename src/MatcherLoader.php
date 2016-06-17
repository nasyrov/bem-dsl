<?php namespace Lego\DSL;

use Iterator;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class MatcherLoader implements MatcherLoaderInterface
{
    /**
     * EngineInterface instance.
     * @var EngineInterface
     */
    protected $engine;
    /**
     * Collection of directories.
     * @var array
     */
    protected $directories = [];

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function load($path)
    {
        if (is_array($path)) {
            foreach ($path as $value) {
                $this->load($value);
            }
        } elseif (in_array($path, $this->directories)) {
            throw new LogicException(sprintf('The "%s" directory path is already being used.', $path));
        } else {
            $this->find($path);

            $this->directories[] = $path;
        }

        return $this;
    }

    /**
     * Finds all the matchers under a specified directory path.
     *
     * @param string $path
     */
    protected function find($path)
    {
        if (!is_dir($path)) {
            throw new LogicException(sprintf('The "%s" directory path does not exist.', $path));
        }

        $directoryIterator = new RecursiveDirectoryIterator($path);
        $iteratorIterator  = new RecursiveIteratorIterator($directoryIterator);
        $regexIterator     = new RegexIterator($iteratorIterator, '/^.+\.php$/i');

        $this->register($regexIterator);
    }

    /**
     * Registers specified matchers.
     *
     * @param Iterator $files
     */
    protected function register(Iterator $files)
    {
        $engine = $this->engine;

        foreach ($files as $file) {
            require_once $file->getPathname();
        }
    }
}
