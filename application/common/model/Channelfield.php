<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */

namespace app\common\model;

use think\Model;

/**
 * 模型自定义字段
 */
class Channelfield extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条记录
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfo($id, $field = '*')
    {
        $result = db('Channelfield')->field($field)->find($id);

        return $result;
    }

    /**
     * 获取单条记录
     * @author 小虎哥 by 2018-4-16
     */
    public function getInfoByWhere($where, $field = '*')
    {
        $cacheKey = "common-Channelfield-getInfoByWhere-".json_encode($where)."-{$field}";
        $result = cache($cacheKey);
        if (!empty($result)) {
            return $result;
        }

        $result = db('Channelfield')->field($field)->where($where)->cache(true,EYOUCMS_CACHE_TIME,"channelfield")->find();
        cache($cacheKey, $result, null, 'channelfield');

        return $result;
    }

    /**
     * 默认模型字段
     * @author 小虎哥 by 2018-4-16
     */
    public function getListByWhere($map = array(), $field = '*', $index_key = '')
    {
        $cacheKey = "common-Channelfield-getListByWhere-".json_encode($map)."-{$field}-{$index_key}";
        $result = cache($cacheKey);
        if (!empty($result)) {
            return $result;
        }

        $result = db('Channelfield')->field($field)
            ->where($map)
            ->order('sort_order asc, channel_id desc, id desc')
            ->cache(true,EYOUCMS_CACHE_TIME,"channelfield")
            ->select();

        if (!empty($index_key)) {
            $result = convert_arr_key($result, $index_key);
        }
        
        cache($cacheKey, $result, null, 'channelfield');

        return $result;
    }
}