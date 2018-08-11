<?php
namespace Eddie\Tencent\Im\Service;

use Eddie\Tencent\Util;

abstract class AbstractService
{
    use Util;

    protected $service;

    public function getUrl($cmd)
    {
        $url = config('im.domain') . config('im.version') . '/' . $this->service . '/' . $cmd;
        return $url;
    }
}