<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午1:25
 */

namespace Controllers\ApiPublic;

use Controllers\BaseController;
use Library\WechatPay;
use Library\AliPay;
use Library\WechatTransfer;
use Library\AliTransfer;

class WechatController extends BaseController
{
    /**
     * 推送接口
     */
    public function payAction()
    {
        $res = AliPay::type('web')->pay([
            //title、order_no、amount、param、notify_url、redirect_url、openid
            'title' => '测试内容',
            'order_no'=>rand(100000,999999),
            'amount'=>0.01,
            'param'=>[],
            'notify_url'=>'e',
            'openid'=>'oUKCUxGItpAG74oms1iw386OrAbg',
        ]);
        response_data(1,'success',$res);
    }

    public function transferAction()
    {
        $res = AliTransfer::pay([
            'title' => '测试内容',
            'order_no'=>rand(100000,999999),
            'amount'=>0.01,
            'account'=>'407055182@qq.com',
        ]);


        /*$res = WechatTransfer::pay([
            'title' => '测试内容',
            'order_no'=>rand(100000,999999),
            'amount'=>1,
            'openid'=>'oUKCUxGItpAG74oms1iw386OrAbg',
        ]);
        return response_data(1,'success',$res);*/
    }

}