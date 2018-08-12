## 腾讯IM ##

### 环境需求

- PHP >= 5.6
- openssl 扩展



### 安装

+ 命令行执行

```shell
$ composer require eddie/tencent
```
或 在composer.json 添加 

```json
"eddie/tencent": "1.0"
```

运行 ```composer update```


+ Laravel 5.5使用包自动发现，所以不需要手动添加ServiceProvider; 版本小于Laravel 5.5需在 `config/app.php` 中注册服务提供者:

```php
'providers' => [
    ...
    
    Eddie\Tencent\Provider\ImServiceProvider::class,
    
    ...
]

'aliases' => [
    ...
    
    'TencentIm' => Eddie\Tencent\Facade\Im::class,
    
    ...
]
```



### 配置

+ 用发布命令将包配置复制到本地配置

```shell
$ php artisan vendor:publish --provider="Eddie\Tencent\Provider\ImServiceProvider"
```

+ 配置, 可以在`.env`中配置

```shell
IM_APPID=12345678
IM_IDENTIFIER=account_identifier
IM_SIGN_EXPIRED=15552000
IM_PRIVATE_KEY=/your_private_key_path/private_key
IM_PUBLIC_KEY=/your_public_key_path/public_key
```


### 使用

```php
/*
 * 生成签名
 */
$identifier = 'user';
$sign = TencentIm::signature()->generate($identifier);

/*
 * 签名校验
 */
if ( TencentIm::signature()->verify($sign, $identifier) ) {
    echo "success";
} else {
    echo "fail";
}
```