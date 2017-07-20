<?php

/**
 * Created by PhpStorm.
 * User: zhoujianjun
 * Date: 2017/7/16
 * Time: 上午9:54
 */

namespace Library;


use Phalcon\Di;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;


class PayNotify implements PayNotifyInterface
{
    private $callBack;

    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        $callBack = $this->callBack;
        $status = $callBack(json_decode($data['param']));
        if ($channel === Config::ALI_CHARGE) {// 支付宝支付
            logMessage('alipay')->log('订单处理状态：'.$status.'========>'.json_encode($data));
        } elseif ($channel === Config::WX_CHARGE) {// 微信支付
            logMessage('wechatpay')->log('订单处理状态：'.$status.'========>'.json_encode($data));
        } elseif ($channel === Config::CMB_CHARGE) {// 招商支付

        } elseif ($channel === Config::CMB_BIND) {// 招商签约

        } else {
            // 其它类型的通知
        }
        return $status;
    }

    /**
     * 设置回调处理方法
     * @param $callBack
     */
    public function setCallBack($callBack)
    {
        $this->callBack = $callBack;
    }
}