<?php namespace Lego\DSL;

class MatcherIterator implements MatcherIteratorInterface
{
    /**
     * Collection of matchers.
     *
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;

    /**
     * Position.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Creates new MatcherIterator instance.
     *
     * @param MatcherCollectionInterface $matcherCollection
     */
    public function __construct(MatcherCollectionInterface $matcherCollection)
    {
        $this->matcherCollection = $matcherCollection;
    }

    public function current()
    {
        return $this->matcherCollection->get($this->position);
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return null !== $this->current();
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
