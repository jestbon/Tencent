<?php

namespace Eddie\Tencent\Im;

class Im
{
    protected $account;

    protected $signature;

    protected $message;

    public function __construct()
    {
        //
    }

    public function test()
    {
        dd($this);
    }

    public function __call($name, $args)
    {

        $class = __NAMESPACE__ . '\\Service\\' . ucfirst($name);

        $this->$name = (new \ReflectionClass($class))->newInstanceArgs($args);

        return $this->$name;
    }

    public function __get($name)
    {
        if (!property_exists($this, $name)) return null;
    }


}