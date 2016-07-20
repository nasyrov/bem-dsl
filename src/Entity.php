<?php namespace Lego\DSL;

class Entity
{
    protected $storage;

    public function __construct(array $storage)
    {
        $this->storage = $storage;
    }

    public function __isset($key)
    {
        return isset($this->storage[$key]);
    }

    public function __set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }
}
