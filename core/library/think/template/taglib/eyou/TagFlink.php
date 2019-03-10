<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 友情链接
 */
class TagFlink extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 获取友情链接
     * @author wengxianhu by 2018-4-20
     */
    public function getFlink($type = 'text', $row = 'null')
    {
        if ($type == 'text') {
            $typeid = 1;
        } elseif ($type == 'image') {
            $typeid = 2;
        }

        $map = array();
        if (!empty($typeid)) {
            $map['typeid'] = array('eq', $typeid);
        }
        $result = M("links")->where($map)
            ->order('sort_order asc')
            ->limit($row)
            ->cache(true,EYOUCMS_CACHE_TIME,"links")
            ->select();
        foreach ($result as $key => $val) {
            $val['target'] = ($val['target'] == 1) ? 'target="_blank"' : 'target="_self"';
            $result[$key] = $val;
        }

        return $result;
    }
}