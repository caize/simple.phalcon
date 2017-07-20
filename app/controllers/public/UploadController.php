<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午9:44
 */
namespace Controllers\ApiPublic;

use Controllers\BaseController;
use Library\Qiniu;

class UploadController extends BaseController
{

    public function fileAction()
    {
        $file='';
        foreach ($_FILES as $key=>$value){
            $file = $value;
            break;
        }

        if(!$file){
            response_data(-1,'文件不存在');
        }
        $qiniu = new Qiniu();
        $key = $qiniu->upload('',$file);
        logMessage('qiniu')->log("$key");
        response_data(1,'Success',['key'=>$key]);
    }

}