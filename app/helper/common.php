<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午9:56
 */

/**
 * 输出对应的json数据
 * 程序结束
 */
if (!function_exists('output_data')) {
    function output_data($code = 1, $msg = 'success', $data = [])
    {
        if(!is_array($data) || empty($data)){
            $data = new stdClass();
        }

        exit(json_encode(compact('code', 'msg', 'data')));
    }
}

/**
 * 输出对应的json数据
 */
if (!function_exists('response_data')) {
    function response_data($code = 1, $msg = 'success', $data = [])
    {
        if(!is_array($data) || empty($data)){
            $data = new stdClass();
        }
        $response = Phalcon\Di::getDefault()->getResponse();
        return $response->setJsonContent(compact('code', 'msg', 'data'));
    }
}

/**
 * 日志类初始化配置
 * $logger->log("This is a message");
 * $logger->error("This is another error");
 * $logger->begin();
 * $logger->commit();
 */
if (!function_exists('logMessage')) {
    function logMessage($name = '')
    {
        $path = '../log/' . date('Y-m-d') . '/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        $logger = new Phalcon\Logger\Adapter\File($path . ($name ? $name : date('Y-m-d')) . ".log");
        return $logger;
    }
}

/**
 * hash算法密码加密
 */
if (!function_exists('passwordHash')) {
    function passwordHash($password, $key = 'zhouxiansheng')
    {
        return password_hash($password, $key);
    }
}

/**
 * hash算法密码解密
 */
if (!function_exists('passwordHash')) {
    function passwordVerify($password, $hashKey)
    {
        return password_verify($password, $hashKey);
    }
}