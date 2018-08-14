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

    public function __call($name, $args)
    {
        $class = __NAMESPACE__ . '\\Service\\' . ucfirst($name);
        $this->$name = (new \ReflectionClass($class))->newInstanceArgs($args);
        return $this->$name;
    }

}