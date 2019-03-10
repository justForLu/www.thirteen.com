<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\model;

use think\Model;

/**
 * 产品参数
 */
class ProductAttr extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条产品的所有参数
     * @author 小虎哥 by 2018-4-3
     */
    public function getProAttr($aid, $field = 'b.*, a.*')
    {
        $result = db('ProductAttribute')->field($field)
            ->alias('a')
            ->join('__PRODUCT_ATTR__ b', 'b.attr_id = a.attr_id', 'LEFT')
            ->where('b.aid', $aid)
            ->order('a.sort_order asc, a.attr_id asc')
            ->select();

        return $result;
    }
}