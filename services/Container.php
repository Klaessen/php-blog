<?php
class Container
{
    protected static $instance;
    protected $instances = [];
    protected $bindings = [];


    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;
    }

    public function make($key)
    {
        if (isset($this->bindings[$key])) {
            return call_user_func($this->bindings[$key], $this);
        }
        throw new Exception("No binding for {$key}");
    }

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