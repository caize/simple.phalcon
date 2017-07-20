<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/18
 * Time: 下午4:31
 */

namespace Models;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Di;

class Model extends \Phalcon\Mvc\Model
{

    /**
     * 获取单条数据
     * @param string $search
     * @param string $field
     * @param string $orderBy
     * @return mixed
     */
    public static function fetchRow($search = '', $field = '*', $orderBy = 'id desc')
    {
        $param = [];
        self::search($search, $param);
        if ($orderBy) {
            $param['order'] = $orderBy;
        }
        if ($field) {
            $param['column'] = $field;
        }
        $param['limit'] = 1;
        /*$param['cache'] = [
            'lifetime' => 3600,
            'key' => '1',
        ];*/
        //var_dump($param);exit;
        return parent::findFirst($param);
    }

    /**
     * 获取多条数据、传入page和pageSize则获取分页数据
     * @param string $search
     * @param string $field
     * @param string $orderBy
     * @param int $pageSize
     * @param int $page
     * @return mixed
     */
    public static function fetchRows($search = '', $field = '*', $orderBy = 'id desc', $pageSize = 0, $page = 0)
    {
        $param = [];
        self::search($search, $param);

        if ($orderBy) {
            $param['order'] = $orderBy;
        }
        if ($field) {
            $param['columns'] = $field;
        }
        if ($pageSize > 0 && $page > 0) {
            $paginator = new PaginatorModel(
                [
                    "data" => parent::find($param),
                    "limit" => $pageSize,
                    "page" => $page,
                ]
            );
            $pageData = $paginator->getPaginate();
            unset($pageData->first, $pageData->before, $pageData->last, $pageData->next, $pageData->limit);
            return $pageData;
        } else {
            return parent::find($param);
        }
    }

    /**
     * 范例：Model::fetchJoin([['a' => 'Models\VueAdmin'], ['Models\VueRole', 'a.role = b.id', 'b']],'','a.id,a.nickname,b.id as role_id,b.name as role_name','a.id desc',5,1)->toArray();
     * 表数组、查询条件、查询字段、字段排序、分页每页数量、页数
     * @param array $tab
     * @param string $search
     * @param string $field
     * @param string $orderBy
     * @param int $pageSize
     * @param int $page
     * @return mixed
     */
    public static function fetchJoin($tab = array(), $search = '', $field = '*', $orderBy = '', $pageSize = 0, $page = 0)
    {
        $dataCallBack = function ($tab, $search, $field, $orderBy, $pageSize, $page) {
            $builder = Di::getDefault()->get("modelsManager")->createBuilder();
            if ($field) {
                $builder->columns($field);
            }
            foreach ($tab as $key => $table) {
                if ($key == 0) {
                    $builder->from($table);
                } else {
                    $builder->join($table[0], $table[1], $table[2]);
                }
            }
            if ($search) {
                $builder->where($search);
            }
            if ($orderBy) {
                $builder->orderBy($orderBy);
            }
            if ($pageSize > 0 && $page > 0) {
                $builder->limit($pageSize, $pageSize * ($page - 1));
            }
            return $builder->getQuery()->execute();
        };

        $countCallBack = function ($tab, $search) {
            $builder = Di::getDefault()->get("modelsManager")->createBuilder();
            foreach ($tab as $key => $table) {
                if ($key == 0) {
                    $builder->from($table);
                } else {
                    $builder->join($table[0], $table[1], $table[2]);
                }
            }
            if ($search) {
                $builder->where($search);
            }
            $res = $builder->columns('COUNT(*) AS nums')->getQuery()->getSingleResult();
            return !empty($res->nums) ? intval($res->nums) : 0;
        };
        $data = $dataCallBack($tab, $search, $field, $orderBy, $pageSize, $page);
        if ($pageSize > 0 && $page > 0) {
            $nums = $countCallBack($tab, $search);
            $result = new \stdClass();
            $result->items = $dataCallBack($tab, $search, $field, $orderBy, $pageSize, $page);
            $result->current = $page;
            $result->total_pages = $nums > 0 ? ceil($nums / $pageSize) : 0;
            $result->total_items = $nums;
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * 重写获取count方法
     * @param string $search
     * @return mixed
     */
    public static function fetchCount($search = '')
    {
        $param = [];
        self::search($search, $param);
        return parent::count($param);
    }

    /**
     * 重写获取Sum方法
     * @param string $search
     * @param string $field
     * @return mixed
     */
    public static function fetchSum($search = '', $field = '')
    {
        $param = [];
        self::search($search, $param);
        $param['column'] = $field;
        return parent::sum($param);
    }

    /**
     * 重写获取平均值方法
     * @param string $search
     * @param string $field
     * @return mixed
     */
    public static function fetchAverage($search = '', $field = '')
    {
        $param = [];
        self::search($search, $param);
        $param['column'] = $field;
        return parent::average($param);
    }

    /**
     * 重写获取最大值方法
     * @param string $search
     * @param string $field
     * @return mixed
     */
    public static function fetchMaximum($search = '', $field = '')
    {
        $param = [];
        self::search($search, $param);
        $param['column'] = $field;
        return parent::maximum($param);
    }

    /**
     * 重写获取最小值方法
     * @param string $search
     * @param string $field
     * @return mixed
     */
    public static function fetchMinimum($search = '', $field = '')
    {
        $param = [];
        self::search($search, $param);
        $param['column'] = $field;
        return parent::maximum($param);
    }

    public static function fetchTrans($callBack)
    {
        $db = Di::getDefault()->get("db");
        $db->begin();
        $flag = $callBack();
        if(in_array(0,$flag) || in_array(false,$flag)){
            $db->rollback();
            return false;
        }else{
            $db->commit();
            return true;
        }
    }

    /**
     * 搜索公共方法
     * @param $search
     * @param $param
     */
    public static function search($search, &$param)
    {
        if ($search) {
            if (is_array($search)) {
                foreach ($search as $key => $value) {
                    $str = strpos($key, ' ') > -1 ? '' : '=';
                    if (is_array($value)) {
                        $value = implode(',', $value);
                        $param['conditions'] = !empty($param['conditions']) ? $param['conditions'] . " AND $key IN($value)" : "$key IN($value)";
                    } else {
                        $param['conditions'] = !empty($param['conditions']) ? $param['conditions'] . " AND $key $str $value" : "$key $str $value";
                    }
                }
            } else {
                $param['conditions'] = $search;
            }
        }
    }

}