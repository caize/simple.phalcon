<?php

namespace Controllers;

/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午5:58
 */

use Library\AppSign;
use Library\WechatAuth;
use Library\JwtAuth;

class BaseController extends \Phalcon\Mvc\Controller
{
    /**
     * 构造函数
     */
    public function onConstruct()
    {

    }

    /**
     * 签名配置
     */
    public function appSign()
    {
        AppSign::check();
    }

    /**
     * 微信授权
     */
    public function wechatAuth()
    {
        $wechat = new WechatAuth();
        $info = $wechat->auth();
        if(empty($info->openid)){
            response_data(-1,'微信授权失败');
        }
    }

    /**
     * token验证
     */
    public function checkToken()
    {

    }

    /**
     * jwt-auth校验
     */
    public function jwtAuth()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $info = JwtAuth::type()->decode($token);
        var_dump($info);exit;
        //var_dump($info);exit;
        //return $info;
    }

    public function route404()
    {
        output_data(-1,'哎呀，服务器好像出现了错误');
    }
}