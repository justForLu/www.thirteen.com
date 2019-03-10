<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */
namespace app\admin\model;

use think\Model;

/**
 * 广告分类
 */
class AdPosition extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条记录
     * @author wengxianhu by 2017-7-26
     */
    public function getInfo($id, $field = '*')
    {
        $result = db('AdPosition')->field($field)->find($id);

        return $result;
    }

    /**
     * 获取多条记录
     * @author wengxianhu by 2017-7-26
     */
    public function getListByIds($ids, $field = '*')
    {
        $map = array(
            'id'   => array('IN', $ids),
        );
        $result = db('AdPosition')->field($field)
            ->where($map)
            ->select();

        return $result;
    }

    /**
     * 默认获取广告分类，包括有效、无效等分类
     * @author wengxianhu by 2017-7-26
     */
    public function getAll($field = '*', $index_key = '')
    {
        $result = db('AdPosition')->field($field)->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }

        return $result;
    }
}