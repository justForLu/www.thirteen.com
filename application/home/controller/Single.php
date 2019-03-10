<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\controller;

class Single extends Base
{
    // 模型标识
    public $nid = 'single';
    // 模型ID
    public $channeltype = '';
    
    public function _initialize() {
        parent::_initialize();
        $channeltype_list = config('global.channeltype_list');
        $this->channeltype = $channeltype_list[$this->nid];
    }

    public function lists($tid)
    {
        $dirname = '';
        if (empty($tid)) {
            $map = array(
                'channeltype'   => $this->channeltype,
                'parent_id' => array('eq', 0),
                'is_hidden' => 0,
                'status'    => 1,
            );
            $res = M('arctype')->where($map)->order('sort_order asc')->limit(1)->find();
            $typeurl = model('Arctype')->getTypeUrl($res);
            header('Location: '.$typeurl);
            exit;
        } else {
            if (strval(intval($tid)) != strval($tid)) {
                $map = array('dirname'=>$tid);
            } else {
                $map = array('id'=>$tid);
            }
            $row = M('arctype')->field('id,dirname')->where($map)->order('sort_order asc')->limit(1)->find();
            $tid = !empty($row['id']) ? intval($row['id']) : 0;
            $dirname = !empty($row['dirname']) ? $row['dirname'] : '';
            /*301重定向到新的伪静态格式*/
            $this->jumpRewriteFormat($tid, $dirname, 'lists');
            /*--end*/
        }

        return action('home/Lists/index', $tid);
    }
}