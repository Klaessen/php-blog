<?php
class Container
{
    protected $instances = [];

    public function set($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete instanceof Closure ? $concrete : function () use ($concrete) {
            return new $concrete($this);
        };
    }

    public function get($abstract)
    {
        if (!isset($this->instances[$abstract])) {
            $this->set($abstract);
        }

        // Resolve the instance by executing the closure if necessary
        return $this->instances[$abstract]($this);
    }

    public function has($abstract)
    {
        return isset($this->instances[$abstract]);
    }
}