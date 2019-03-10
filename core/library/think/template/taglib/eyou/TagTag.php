<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 标签
 */
class TagTag extends Base
{
    public $aid = 0;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->aid = I('param.aid/d', 0);
    }

    /**
     * 获取标签
     * @author wengxianhu by 2018-4-20
     */
    public function getTag($getall = 0, $typeid = '', $aid = 0, $row = 30, $sort = 'now')
    {
        $aid = !empty($aid) ? $aid : $this->aid;
        $getall = intval($getall);
        $result = false;
        $condition = array();

        if ($getall == 0 && $aid > 0) {
            $condition['aid'] = array('eq', $aid);
            $result = db('taglist')
                ->field('*, tid AS tagid')
                ->where($condition)
                ->limit($row)
                ->select();

        } else {
            if (!empty($typeid)) {
                $condition['typeid'] = array('in', $typeid);
            }
            if($sort == 'rand') $orderby = 'rand() ';
            else if($sort == 'week') $orderby=' weekcc DESC ';
            else if($sort == 'month') $orderby=' monthcc DESC ';
            else if($sort == 'hot') $orderby=' count DESC ';
            else if($sort == 'total') $orderby=' total DESC ';
            else $orderby = 'add_time DESC  ';

            $result = db('tagindex')
                ->field('*, id AS tagid')
                ->where($condition)
                ->orderRaw($orderby)
                ->limit($row)
                ->select();
        }

        foreach ($result as $key => $val) {
            $val['link'] = url(MODULE_NAME.'/Tags/lists', array('tagid'=>$val['tagid']));
            $result[$key] = $val;
        }

        return $result;
    }
}