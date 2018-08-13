<?php

namespace Eddie\Tencent\Im\Service;

use Eddie\Tencent\Im\MessageBag;

class Message extends AbstractService
{
    const CALLBACK_BEFORE = 'C2C.CallbackBeforeSendMsg';
    const CALLBACK_AFTER  = 'C2C.CallbackAfterSendMsg';

    protected $service = 'openim';

    private $fromAccount;

    private $toAccount;


    private $callbackBefore = false;

    private $callbackAfter = false;

    private $msgBody = [];


    public function __construct()
    {

    }

    public function send($identifier)
    {
        /*
         * TODO ...
         */
        $data = [];

        echo "消息发送[to:$identifier]";

        $res = self::postRequest($this->getUrl('sendmsg'), $data);
    }

    /**
     * 接收解析
     *
     * @author Eddie
     *
     * @param array $msg
     * @return $this
     */
    public function parse(array $msg)
    {
        foreach ($msg as $attr => $val) {
            switch ($attr) {
                case 'CallbackCommand': // 回调命令
                    if (self::CALLBACK_BEFORE == $val) {
                        $this->callbackBefore = true;
                    } else if (self::CALLBACK_AFTER == $val) {
                        $this->callbackAfter = true;
                    }
                    break;

                case 'MsgBody':
                    foreach ($val as $item) {
                        $this->msgBody[] = new MessageBag($item);
                    }
                    break;

                default:
                    $attribute = self::convertToCamel($attr);
                    if (property_exists($this, $attribute)) {
                        $this->$attribute = $val;
                    }
                    break;
            }
        }

        return $this;
    }

    /**
     * 向"MsgBody"添加消息内容
     *
     * @author Eddie
     *
     * @param MessageBag $msgBag
     * @return $this
     */
    public function append(MessageBag $msgBag)
    {
        $this->msgBody[] = $msgBag;
        return $this;
    }

    /**
     * 清空"MsgBody"消息内容
     *
     * @author Eddie
     *
     * @return $this
     */
    public function flush()
    {
        $this->msgBody = [];
        return $this;
    }


    public function __call($name, $args)
    {
        if (property_exists($this, $name)) {
            $this->$name = $args[0];
        }
        return $this;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            switch ($name) {
                case 'is_callback':
                case 'isCallback':
                //case 'callback':
                    return $this->callbackBefore || $this->callbackAfter;

                default:
                    return null;
            }
        }
    }

    public function handleCallbackBeforeSend(\Closure $callback)
    {
        if ($this->callbackBefore) {
            $callback($this);
        }
        return $this;
    }

    public function handleCallbackAfterSend(\Closure $callback)
    {
        if ($this->callbackAfter) {
            $callback($this);
        }
        return $this;
    }

}