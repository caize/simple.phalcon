<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/20
 * Time: 下午12:01
 */
namespace Controllers\ApiPublic;


use Library\JwtAuth;

class JwtAuthController extends \Controllers\BaseController
{

    public function indexAction()
    {
        $data = [
            'user_id'=>1,
            'nickname'=>'周先生',
        ];
        $result['token'] = JwtAuth::type()->encode($data);
        response_data(1,'success',$result);
    }

    public function checkAction()
    {
        $this->jwtAuth();
    }
}