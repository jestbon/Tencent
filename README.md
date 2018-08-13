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
"eddie/tencent": "dev-master"
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

签名 `signature` 
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

消息 `message`
```php
/*
 * 解析IM回调消息
 */
$message = TencentIm::message()->parse(request()->all());

/*
 * 读取消息
 */
$message->fromAccount; // 消息发送方帐号
$message->toAccount; // 消息接收方帐号
$message->isCallback; // 或 "$message->is_callback", 是否IM回调, 返回"true"、"false"
$message->callbackBefore; // 是否发送消息之前回调, 返回"true"、"false"
$message->callbackAfter; // 是否发送消息之后回调, 返回"true"、"false"

$message->msgBody; // 消息内容
$message->msgBody->text; // 文本消息 - 消息内容
$message->msgBody->data; // 自定义消息 - 自定义消息数据
$message->msgBody->desc; // 自定义消息 - 自定义消息描述信息
$message->msgBody->ext; // 自定义消息 - 扩展字段
/*
 * 注:
 *     详细参考腾讯IM [消息格式描述](https://cloud.tencent.com/doc/product/269/%E6%B6%88%E6%81%AF%E6%A0%BC%E5%BC%8F%E6%8F%8F%E8%BF%B0)
 *     属性名 以 IM消息格式中所定义的字段名的小驼峰命名
 */
```