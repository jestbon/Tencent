<?php


namespace Eddie\Tencent\Im\Service;


use Eddie\Tencent\Util;

class Account extends AbstractService
{
    protected $service = 'im_open_login_svc';

    private $attrs = [
        'identifier' => '', // 用户名，长度不超过 32 字节
        'nick'       => '', // 用户昵称
        'faceUrl'    => '', // 用户头像URL
        'type'       => 0   // 帐号类型，开发者默认无需填写，值0表示普通帐号，1表示机器人帐号
    ];

    private $sig;

    function __construct()
    {
        $args = func_get_args();
        if ($args) {
            if (isset($args[0]['sig'])) {
                $this->sig = $args[0]['sig'];
                return $this;
            }
        }
        throw new \Exception('paramater "sig" is required.');
    }

    /**
     * 独立模式账号导入
     *
     * @param $identifier
     */
//    public function create($identifier = null, $nickname = null, $faceUrl = null, $type = 0)
//    {
//        $identifier && $this->identifier = $identifier;
//        $nickname && $this->nickname = $nickname;
//        $faceUrl && $this->faceUrl = $faceUrl;
//        $type == 1 && $this->type = 1;
//
//        $data = $this->transfor();
//
//        /*
//         * TODO ...
//         */
//        echo "独立模式账号导入[user: ".json_encode($data)."]";
//
//        $res = $this->postRequest($this->getUrl('account_import'), $data);
//    }

    /**
     * 独立模式帐号批量导入接口
     *
     * @param array $identifiers
     */
    public function import(array $identifiers)
    {
        $data = [];
        /*
         * TODO ...
         */
        echo '独立模式帐号批量导入';

        $res = $this->postRequest($this->getUrl('multiaccount_import'), $data);
    }

    private function transfor()
    {
        return [
            'Identifier' => $this->identifier,
            'Nick' => $this->nickname,
            'FaceUrl' => $this->faceUrl,
            'Type' => $this->type
        ];
    }


    public function __call($name, array $args)
    {
        if (array_key_exists($name, $this->attrs)) {
            $this->attrs[$name] = $args[0];
        } else if ($name === 'nickname') {
            $this->attrs['nick'] = $args[0];
        }
        return $this;
    }

    public function __get($name)
    {
        //dd(['name' => $name]);
        if (array_key_exists($name, $this->attrs)) {
            return $this->attrs[$name];
        }
        return null;
    }

    public function save()
    {
        $data = [
            'Identifier' => $this->attrs['identifier'],
            'Nick'       => $this->attrs['nick'],
            'FaceUrl'    => $this->attrs['faceUrl'],
            'Type'       => $this->attrs['type']
        ];
        $data = array_filter($data);
        //dd([$this->getUrl('account_import'), $data]);

        /*
         * https://console.tim.qq.com/v4/im_open_login_svc/account_import?usersig=xxx&identifier=admin&sdkappid=88888888&random=99999999&contenttype=json
         */
        $url = $this->getUrl('account_import') . '?' . http_build_query([
            'usersig' => $this->sig,
            'identifier' => config('im.identifier'),
            'sdkappid' => config('im.appid'),
            'random' => Util::makeMsgRandom(),
            'contenttype' => 'json'
        ]);
        //dd(['url' => $url, 'data' => $data]);

        try {
            $result = Util::postRequest($url, json_encode($data));
            return json_decode($result, true);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function setRobot()
    {
        $this->attrs['type'] = 1;
        return $this;
    }

    public function isRobot()
    {
        return $this->attrs['type'] === 0;
    }

}