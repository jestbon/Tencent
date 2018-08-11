<?php


namespace Eddie\Tencent\Im\MessageEntity;


class Location extends Base
{
    protected $fillable = ['desc', 'latitude', 'longitude'];
}