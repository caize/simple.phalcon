<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午2:41
 */

namespace Library;


use Phalcon\Di;
use Models\VueToken;

class UserToken
{

    /**
     * 验证签名数据、注入app类
     * @param $app
     * @return array
     */
    public static function check($app)
    {
        $data = VueToken::findFirst([
            'conditions' => 'token = :token AND status = :status',
            'columns' => 'type,user_id,expire_time',
            'order' => 'id DESC',
            'bind' => ['token' => $app->response->getHeaders()->get('Token'), 'status' => 1],
        ]);
        var_dump($data);


        /** 获取签名配置信息 */
        $sign_key = $app->config->api_sign->key;
        $sign_status = $app->config->api_sign->status;
        $sign_expire = $app->config->api_sign->expire_time;
        /** 获取post请求信息 */
        $data = $app->request->getPost();
        if (empty($data['sign']) || empty($data['timestamp'])) {
            return ['message' => '参数异常', 'code' => '-1', 'data' => []];
        }
        $sign_origin = $data['sign'];
        $timestamp = $data['timestamp'];
        unset($data['sign'], $data['timestamp']);
        $keys = array_keys($data);
        sort($keys);
        $signStr = '';
        foreach ($keys as $value) {
            if (is_array($data[$value]) && $data[$value]) {
                foreach ($data[$value] as $k => $v) {
                    $signStr .= $value . '[' . $k . ']' . '=' . $v . '&';
                }
            } else {
                $signStr .= $value . '=' . $data[$value] . '&';
            }
        }
        $signStr = $keys ? $signStr . 'appKey=' . $sign_key . '&timestamp=' . $timestamp : 'appKey=' . $sign_key . '&timestamp=' . $timestamp;
        $sign = strtoupper(md5($signStr));
        if ($sign_status) {
            if ($sign != $sign_origin) {
                output_data(-1, '签名信息错误');
            }
            if ($timestamp < time() - $sign_expire) {
                output_data(-1, '签名信息已过期');
            }
        }
    }

}