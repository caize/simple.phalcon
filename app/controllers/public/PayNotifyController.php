<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午1:25
 */

namespace Controllers\ApiPublic;

use Controllers\BaseController;
use Payment\Common\PayException;
use Payment\Client\Notify;
use Library\PayNotify;
use Library\WechatPay;
use Library\AliPay;

class PayNotifyController extends BaseController
{
    /**
     * 支付回调处理接口
     */
    public function run()
    {
        $callback = new PayNotify();
        $callback->setCallBack($this->pay_service->run());
        $data = file_get_contents("php://input");
        if($data){
            $type = 'wx_charge';
            $config = WechatPay::config();
        }else{
            $type = 'ali_charge';
            $config = AliPay::config();
        }

        try {
            $ret = Notify::run($type, $config, $callback);
            echo $ret;
        } catch (PayException $e) {
            logMessage('pay_notify')->log('fail------------>'.$e->errorMessage());
            exit;
        }
    }


}