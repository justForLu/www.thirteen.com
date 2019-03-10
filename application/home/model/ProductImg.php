<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\model;

use think\Model;

/**
 * 产品图片
 */
class ProductImg extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条产品的所有图片
     * @author 小虎哥 by 2018-4-3
     */
    public function getProImg($aid, $field = '*')
    {
        $result = db('ProductImg')->field($field)
            ->where('aid', $aid)
            ->order('sort_order asc')
            ->select();

        return $result;
    }
}