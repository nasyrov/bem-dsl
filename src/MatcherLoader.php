<?php namespace Lego\DSL;

use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class MatcherLoader implements MatcherLoaderInterface
{
    /**
     * Engine instance.
     * @var Engine
     */
    protected $engine;
    /**
     * Collection of directories.
     * @var array
     */
    protected $directories = [];

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function addDirectory($path)
    {
        if (is_array($path)) {
            foreach ($path as $value) {
                $this->addDirectory($value);
            }
        } elseif (!is_dir($path)) {
            throw new LogicException(sprintf('The "%s" directory path does not exist.', $path));
        } elseif (false !== array_search($path, $this->directories)) {
            throw new LogicException(sprintf('The "%s" directory path is already being used.', $path));
        } else {
            $this->load($path);

            $this->directories[] = $path;
        }

        return $this;
    }

    /**
     * Registers all the matchers in the given directory path.
     *
     * @param string $path
     */
    protected function load($path)
    {
        $engine = $this->engine;

        $recursiveDirectoryIterator = new RecursiveDirectoryIterator($path);
        $recursiveIteratorIterator  = new RecursiveIteratorIterator($recursiveDirectoryIterator);
        $regexIterator              = new RegexIterator($recursiveIteratorIterator, '/^.+\.php$/i');

        /**
         * @var $entry \SplFileInfo
         */
        foreach ($regexIterator as $entry) {
            require_once $entry->getPathname();
        }
    }
}
