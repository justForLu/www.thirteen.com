<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * 友情链接
 */
class Links extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }
}