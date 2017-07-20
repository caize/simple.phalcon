<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午2:27
 */

return new \Phalcon\Config([

    /**
     * 微信授权认证配置
     */
    'wechat_auth' => [
        'app_id' => '',
        'secret' => '',
    ],

    /**
     * 签名信息配置信息
     */
    'api_sign' => [
        'key' => '',
        'status' => true,
        'expire_time' => 120,
    ],
    /**
     * 七牛上传配置信息
     */
    'qiniu' => [
        'accessKey' => '',
        'secretKey' => '',
        'bucket' => '',
        'url' => '',
    ],
    /**
     * Jpush极光推送配置
     */
    'jpush' => [
        'default' => [
            'app_key' => '',
            'master_secret' => '',
            'production' => false,
        ]
    ],
    /**
     * 微信支付配置信息
     */
    'wechat_pay' => [
        'app' => [
            'app_id' => '',
            'app_secret' => '',
            'mch_id' => '',
            'md5_key' => '',
            'cert_pem' => '',
            'key_pem' => '',
        ],
        'web' => [
            'app_id' => '',
            'app_secret' => '',
            'mch_id' => '',
            'md5_key' => '',
            'cert_pem' => BASE_PATH.'/resource/wechat/apiclient_cert.pem',
            'key_pem' => BASE_PATH.'/resource/wechat/apiclient_key.pem',
        ]
    ],
    /**
     * 支付宝支付
     */
    'alipay' => [
        'app_id' => '',
        'partner' => '',
        'seller_id'=>'',
        'ali_public_key' => BASE_PATH.'/resource/alipay/alipay_public_key.pem',
        'rsa_private_key' => BASE_PATH.'/resource/alipay/rsa_private_key.pem',
    ],
    /**
     * JwtAuth token授权配置
     */
    'jwt_auth'=>[
        'type'=>'HS256',
        'key'=>'12312312312312',
        'privete'=>BASE_PATH.'/resource/jwtauth/id_ras',
        'public'=>BASE_PATH.'/resource/jwtauth/id_ras.pub',
    ],
]);