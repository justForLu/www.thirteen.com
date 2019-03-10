<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\controller;

class Tags extends Base
{
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 标签主页
     */
    public function index()
    {
        return $this->lists();
    }

    /**
     * 标签列表
     */
    public function lists()
    {
        $param = I('param.');
        
        $tagid = isset($param['tagid']) ? $param['tagid'] : '';
        $tag = isset($param['tag']) ? trim($param['tag']) : '';
        if (!empty($tag)) {
            $tagindexInfo = M('tagindex')->where('tag', $tag)->find();
        } elseif (intval($tagid) > 0) {
            $tagindexInfo = M('tagindex')->where('id', $tagid)->find();
        }

        if (!empty($tagindexInfo)) {
            $tagid = $tagindexInfo['id'];
            $tag = $tagindexInfo['tag'];
            //更新浏览量和记录数
            $map = array(
                'tid'   => array('eq', $tagid),
                'arcrank'   => array('gt', -1),
            );
            $total = M('taglist')->where($map)
                ->count('tid');
            M('tagindex')->where(array('id'=>array('eq', $tagid)))
                ->inc('count')
                ->inc('weekcc')
                ->inc('monthcc')
                ->update(array('total'=>$total));

            $ntime = getTime();
            $oneday = 24 * 3600;

            //周统计
            if(ceil( ($ntime - $tagindexInfo['weekup'])/$oneday ) > 7)
            {
                M('tagindex')->where(array('id'=>array('eq', $tagid)))->update(array('weekcc'=>0, 'weekup'=>$ntime));
            }

            //月统计
            if(ceil( ($ntime - $tagindexInfo['monthup'])/$oneday ) > 30)
            {
                M('tagindex')->where(array('id'=>array('eq', $tagid)))->update(array('monthcc'=>0, 'monthup'=>$ntime));
            }
        }

        $field_data = array(
            'tag'   => $tag,
            'tagid'   => $tagid,
        );
        $eyou = array(
            'field'  => $field_data,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        return $this->fetch('template/'.$this->theme_style.'/lists_tags.'.$this->view_suffix);
    }
}