<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午2:22
 */

namespace Library;

use Phalcon\Di;
use Payment\Common\PayException;
use Payment\Client\Charge;

class AliPay
{

    private static $pay_type;

    /**
     *
     * @return array
     */
    public static function config($data = array())
    {
        /** 重组配置参数 */
        $config = Di::getDefault()->get("commonConfig")->alipay;
        return [
            'use_sandbox' => !empty($config['use_sandbox']) ? $config['use_sandbox'] : false,// 是否使用沙箱环境
            'partner'                   => $config['partner'],
            'app_id'                    => $config['app_id'],
            'sign_type'                 => 'RSA',// RSA  RSA2
            'ali_public_key'            => $config['ali_public_key'],
            'rsa_private_key'           => $config['rsa_private_key'],
            'limit_pay'                 => [],//'balance'余额、moneyFund'余额宝、'debitCardExpress'借记卡快捷、'creditCard'信用卡、'creditCardExpress'信用卡快捷、'creditCardCartoon'信用卡卡通、'credit_group'信用支付类型（包含信用卡卡通、信用卡快捷、花呗、花呗分期）
            'notify_url'                => !empty($data['notify_url'])?$data['notify_url']:'http://baidu.com',
            'return_url'                => !empty($data['return_url'])?$data['return_url']:'',
            'return_raw'                => false,// 在处理回调时，是否直接返回原始数据，默认为 true
        ];
    }

    /**
     * 设置微信支付的类型【及时到账、移动支付、H5支付、扫码支付、条码支付】
     * @param string $type
     * @return WechatPay
     */
    public static function type($type = 'app')
    {
        if (!in_array($type, ['web', 'app', 'wap', 'qr', 'bar'])) {
            output_data(-1, '支付宝支付类型错误');
        }
        self::$pay_type = 'ali_' . ($type ? $type : 'app');
        return new self();
    }

    /**
     * 支付宝支付[ title、order_no、amount、param、notify_url、redirect_url、openid ]
     * @param array $data
     * @return bool|mixed
     */
    public function pay($data = array())
    {
        $config = self::config($data);
        /** 重组支付信息参数 */
        $payData = [
            'body' => $data['title'],//付款内容
            'subject' => $data['title'],//付款标题
            'order_no' => $data['order_no'],//订单号
            'timeout_express' => time() + 1800,//超时时间
            'amount' => $data['amount'],//金额（元）
            'return_param' => json_encode($data['param']),
        ];
        $str = Charge::run(self::$pay_type, $config, $payData);
        var_dump($str);exit;
        /** 开始执行 */
        try {
            $str = Charge::run(self::$pay_type, $config, $payData);
            return json_decode($str,true);
        } catch (PayException $e) {
            return false;
        }
    }


}