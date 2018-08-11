<?php

namespace Eddie\Tencent\Im;

use Eddie\Tencent\Im\MessageEntity\Custom;
use Eddie\Tencent\Im\MessageEntity\Face;
use Eddie\Tencent\Im\MessageEntity\Location;
use Eddie\Tencent\Im\MessageEntity\Text;

class MessageBag
{
    protected $msgType;

    protected $msgContent;

    private $entity;


    private $mapping = [
        'TIMTextElem' => Text::class,
        'TIMCustomElem' => Custom::class,
        'TIMLocationElem' => Location::class,
        'TIMFaceElem' => Face::class,
    ];


    public function __construct(array $data)
    {
        $this->msgType = $data['MsgType'];

        if (isset($data['MsgContent']) && !empty($data['MsgContent'])) {
            if (isset($this->mapping[$this->msgType])) {
                $this->entity = (new \ReflectionClass($this->mapping[$this->msgType]))->newInstanceArgs([$data['MsgContent']]);
            }
        }
    }

    public function format()
    {
        return [
            'MsgType' => $this->msgType,
            'MsgContent' => []
        ];
    }

    public function __get($name)
    {
        $attr = strtolower($name);
        return $this->entity->$attr;
    }

    public function __call($name, $args)
    {
        $attr = strtolower($name);
        if (isset($this->msgContent[$attr])) {
            $this->msgContent[$attr] = $args[0];
        }
        return $this;
    }

}