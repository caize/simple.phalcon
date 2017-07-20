<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午9:44
 */

namespace Controllers\Api;

use Controllers\BaseController;
use Models\VueAdmin;
use Models\Model;

class UploadController extends BaseController
{

    public function indexAction()
    {

        //$this->modelsCache->save("my-data", [1, 2, 3, 4, 5]);
        //var_dump($this->modelsCache->get('my-data'));
        $list = Model::fetchJoin([['a' => 'Models\VueAdmin'], ['Models\VueRole', 'a.role = b.id', 'b']], [], 'a.id,a.nickname,b.id as role_id,b.name as role_name', 'a.id desc');
        return response_data(1,'success',$list);

        $list = VueAdmin::fetchRows(['id'=>[1,2,3]],'id,username','id desc',5,1);
        return response_data(1,'success',$list);
        print_r($list->total_items);exit;
        foreach ($list as $key=>$value){
            var_dump($value->id);
        }
    }

}