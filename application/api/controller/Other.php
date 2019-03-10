<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\api\controller;

class Other extends Base
{
    /*
     * 初始化操作
     */
    
    public function _initialize() {
        parent::_initialize();
        session('user'); // 哪里用到 session_id() , 哪个文件就加上这行代码
    }

    /**
     * 广告位js
     */
    public function other_show()
    {
        $pid = I('pid/d',1);
        $row = I('row/d',1);
        $where = array(
            'pid'=>$pid,
            'status'=>1,
            'start_time'=>array('lt', getTime()),
        );
        $ad = M("ad")->where($where)
            ->where('end_time', ['>', getTime()], ['=', 0], 'or')
            ->order("sort_order asc")
            ->limit($row)
            ->cache(true,EYOUCMS_CACHE_TIME, 'ad')
            ->select();
        $this->assign('ad',$ad);
        return $this->fetch();
    }
}