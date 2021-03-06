<?php
namespace Eddie\Tencent\Test;

use Eddie\Tencent\Im\Im as TencentIm;

class TecentImSignatureTest extends TestCase
{

    protected $signature;


    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('im.appid', '1400001052');
        $this->app['config']->set('im.private_key', __DIR__ . '/test_private_key');
        $this->app['config']->set('im.public_key', __DIR__ . '/test_public_key');

        $this->signature = (new TencentIm())->signature();
    }

    public function testSignatureGenerate()
    {
        $identifier = 'demo_user';

        $sig = $this->signature->generate($identifier);

        $verify = $this->signature->verify($sig, $identifier);

        $this->assertTrue($verify);
    }
}