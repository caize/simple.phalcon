<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 上午11:32
 */

namespace Library;

use JPush\Client as JPushLib;
use Phalcon\Di;

class Jpush
{
    private static $client;
    private static $config;

    /**
     * 初始化配置信息，分为极光推送多配置
     * @param string $type
     * @return Jpush
     */
    public static function type($type = 'default')
    {
        $jpush_config = Di::getDefault()->get("commonConfig")->jpush;
        $app_key = $jpush_config->$type->app_key;
        $master_secret = $jpush_config->$type->master_secret;
        self::$client = new JPushLib($app_key, $master_secret);
        self::$config = $jpush_config->$type;
        return new self();
    }

    /**
     * 推送给指定用户
     * @param array $client = ['registration_id'=>[]] or ['alias'=>[]]
     * @param array $msg = ['title'=>'标题','content'=>'内容']
     * @param bool $is_message 是否是自定义消息，是则不不推送通知
     * @param string $platform 平台类型：ios android all
     * @return bool
     */
    public function sendClient($client = array(), $msg = array(), $is_message = false, $platform = 'all')
    {
        if (!self::$client) {
            return false;
        }
        $response = self::$client->push()->setPlatform($platform ? $platform : 'all');
        if(!empty($client['registration_id'])){
            $response->addRegistrationId($client['registration_id']);
        }
        if(!empty($client['alias'])){
            $response->addAlias($client['alias']);
        }
        if($is_message){
            $response->message(json_encode($msg));
        }else{
            $response->setNotificationAlert($msg['title'])->message(json_encode($msg));
        }
        return $response->options([
            'apns_production' => self::$config->production,
            'sound'=>'',
            'badge'=>'+1',
            'extras'=>$msg
        ])->send();
    }

    /**
     * 极光推送给所有用户
     * @param string $platform
     * @param array $msg
     * @return bool
     */
    public function sendAll($msg = array(), $platform = 'all')
    {
        if (!self::$client) {
            return false;
        }
        $res = self::$client->push()
            ->setPlatform($platform ? $platform : 'all')
            ->addAllAudience()
            ->setNotificationAlert($msg['title'])
            ->message(json_encode($msg))
            ->options(['apns_production' => self::$config->production])
            ->send();
        return $res;
    }


}