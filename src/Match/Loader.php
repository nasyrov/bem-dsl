<?php namespace BEM\DSL\Match;

use BEM\DSL\EngineInterface;
use Iterator;
use LogicException;

class Loader implements LoaderInterface
{
    protected $engine;
    protected $directories = [];

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function setDirectories(array $directories)
    {
        foreach ($directories as $directory) {
            $this->load($directory);
        }

        return $this;
    }

    public function setDirectory($directory)
    {
        if (in_array($directory, $this->directories)) {
            throw new LogicException(sprintf('The "%s" directory is already registered.', $directory));
        } else {
            $this->load($directory);
            $this->directories[] = $directory;
        }

        return $this;
    }

    /**
     * @param string $directory
     */
    protected function load($directory)
    {
        if (!is_dir($directory)) {
            throw new LogicException(sprintf('The "%s" directory does not exist.', $directory));
        }

        $directoryIterator = new RecursiveDirectoryIterator($directory);
        $iteratorIterator  = new RecursiveIteratorIterator($directoryIterator);
        $regexIterator     = new RegexIterator($iteratorIterator, '/^.+\.php$/i');

        $this->register($regexIterator);
    }

    /**
     * @param Iterator $files
     */
    protected function register(Iterator $files)
    {
        $dsl = $this->engine;

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            require_once $file->getPathname();
        }
    }
}
