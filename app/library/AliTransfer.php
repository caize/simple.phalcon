<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午5:23
 */

namespace Library;

use Payment\Common\PayException;
use Payment\Client\Transfer;
use Library\AliPay;


class AliTransfer
{

    /**
     * 微信企业转账
     * @param array $data
     * @return bool|mixed
     */
    public static function pay($data = array())
    {
        $data = [
            'trans_no' => $data['order_no'],
            'payee_type' => 'ALIPAY_LOGONID',
            'payee_account' => $data['account'],
            'amount' => $data['amount'],
            'remark' => $data['title'],
        ];
        var_dump($ret = Transfer::run('ali_transfer', AliPay::config(), $data));
        var_dump($ret);exit;
        try {
            $ret = Transfer::run('ali_transfer', AliPay::config(), $data);
            return !empty($ret['is_success']) ? true : false;
        } catch (PayException $e) {
            return false;
        }
    }


}