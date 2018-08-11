<?php


namespace Eddie\Tencent\Im\Service;


class Account extends AbstractService
{
    protected $service = 'im_open_login_svc';

    private $identifier;

    private $nickname;

    private $faceUrl;

    private $type = 0;

    /**
     * 独立模式账号导入
     *
     * @param $identifier
     */
    public function create($identifier = null, $nickname = null, $faceUrl = null, $type = 0)
    {
        $identifier && $this->identifier = $identifier;
        $nickname && $this->nickname = $nickname;
        $faceUrl && $this->faceUrl = $faceUrl;
        $type == 1 && $this->type = 1;

        $data = $this->transfor();

        /*
         * TODO ...
         */
        echo "独立模式账号导入[user: ".json_encode($data)."]";

        $res = $this->postRequest($this->getUrl('account_import'), $data);
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function setFace($face)
    {
        $this->faceUrl = $face;
        return $this;
    }

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
}