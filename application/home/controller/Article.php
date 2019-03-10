<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\controller;

class Article extends Base
{
    // 模型标识
    public $nid = 'article';
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
                'parent_id' => 0,
                'is_hidden' => 0,
                'status'    => 1,
            );
    	} else {
            if (strval(intval($tid)) != strval($tid)) {
                $map = array('dirname'=>$tid);
            } else {
                $map = array('id'=>$tid);
            }
        }
        $row = M('arctype')->field('id,dirname')->where($map)->order('sort_order asc')->limit(1)->find();
        $tid = !empty($row['id']) ? intval($row['id']) : 0;
        $dirname = !empty($row['dirname']) ? $row['dirname'] : '';

        /*301重定向到新的伪静态格式*/
        $this->jumpRewriteFormat($tid, $dirname, 'lists');
        /*--end*/

        return action('home/Lists/index', $tid);
    }

    public function view($aid)
    {
        $result = model('Article')->getInfo($aid);
        if (empty($result)) {
            $this->error('页面不存在！');
            exit;
        } elseif ($result['arcrank'] == -1) {
            $this->success('待审核稿件，你没有权限阅读！');
            exit;
        }
        // 外部链接跳转
        if ($result['is_jump'] == 1) {
            header('Location: '.$result['jumplinks']);
            exit;
        }
        /*--end*/

        $tid = $result['typeid'];
        $arctypeInfo = model('Arctype')->getInfo($tid);
        /*301重定向到新的伪静态格式*/
        $this->jumpRewriteFormat($aid, $arctypeInfo['dirname'], 'view');
        /*--end*/

        return action('home/View/index', $aid);
    }
}