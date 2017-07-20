<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/20
 * Time: 下午12:01
 */
namespace Controllers\ApiPublic;


class CacheController extends \Controllers\BaseController
{

    public function indexAction()
    {
        $this->redis_cache->save('test',[1,2,3,3,3,3,3]);
        var_dump($this->redis_cache->get('test'));
    }


}