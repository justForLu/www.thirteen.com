<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 基类
 */
class Base
{
    //构造函数
    function __construct()
    {
        // 控制器初始化
        $this->_initialize();
    }

    // 初始化
    protected function _initialize()
    {
        
    }

    /**
     * 在typeid传值为目录名称的情况下，获取栏目ID
     */
    public function getTrueTypeid($typeid = '')
    {
        /*tid为目录名称的情况下*/
        if (!empty($typeid) && strval($typeid) != strval(intval($typeid))) {
            $typeid = M('Arctype')->where(array('dirname'=>$typeid))->cache(true,EYOUCMS_CACHE_TIME,"arctype")->getField('id');
        }
        /*--end*/

        return $typeid;
    }
}