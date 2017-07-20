<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午1:25
 */
namespace Controllers\ApiPublic;

use Controllers\BaseController;
use Library\Jpush;

class JpushController extends BaseController
{
    /**
     * 推送接口
     */
    public function sendAction()
    {
        $result = Jpush::type('default')->sendAll(['title'=>'测试信息','content'=>'测试内容']);
        return response_data(1,'success',$result);
    }




}