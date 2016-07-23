<?php namespace BEM\DSL\Entity;

class Entity implements EntityInterface
{
    protected $storage;

    public function __construct(array $storage)
    {
        $this->storage = $storage;
    }

    public function __set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function &__get($key)
    {
        return $this->storage[$key];
    }
}
