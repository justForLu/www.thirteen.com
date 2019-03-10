<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\controller;

class View extends Base
{
    // 模型标识
    public $nid = '';
    // 模型ID
    public $channel = '';
    // 模型名称
    public $modelName = '';

    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 内容页
     */
    public function index($aid = '')
    {
        $aid = intval($aid);
        $archivesInfo = M('archives')->field('a.typeid, a.channel, b.nid, b.ctl_name')
            ->alias('a')
            ->join('__CHANNELTYPE__ b', 'a.channel = b.id', 'LEFT')
            ->where('a.aid',$aid)
            ->find();
        if (empty($archivesInfo)) {
            $this->error('文档不存在！');
        }
        $this->nid = $archivesInfo['nid'];
        $this->channel = $archivesInfo['channel'];
        $this->modelName = $archivesInfo['ctl_name'];

        $result = model($this->modelName)->getInfo($aid);
        if ($result['arcrank'] == -1) {
            $this->success('待审核稿件，你没有权限阅读！');
        }
        // 外部链接跳转
        if ($result['is_jump'] == 1) {
            header('Location: '.$result['jumplinks']);
            exit;
        }
        /*--end*/

        $tid = $result['typeid'];
        $arctypeInfo = model('Arctype')->getInfo($tid);
        /*自定义字段的数据格式处理*/
        $arctypeInfo = $this->fieldLogic->getTableFieldList($arctypeInfo, config('global.arctype_channel_id'));
        /*--end*/
        if (!empty($arctypeInfo)) {
            $arctypeInfo['typelitpic'] = $arctypeInfo['litpic'];
            // 是否有子栏目，用于标记【全部】选中状态
            $arctypeInfo['has_children'] = model('Arctype')->hasChildren($tid);
        }
        $result = array_merge($arctypeInfo, $result);

        // 文档链接
        $result['arcurl'] = '';
        if ($result['is_jump'] != 1) {
            $result['arcurl'] = arcurl('home/View/index', $result, true, true);
        }
        /*--end*/
        
        /*获取当前页面URL*/
        $result['pageurl'] = request()->domain().request()->url();
        /*--end*/

        // seo
        $result['seo_title'] = set_arcseotitle($result['title'], $result['seo_title'], $result['typename']);

        $result = view_logic($aid, $this->channel, $result); // 模型对应逻辑

        /*自定义字段的数据格式处理*/
        $result = $this->fieldLogic->getChannelFieldList($result, $this->channel);
        /*--end*/

        $eyou = array(
            'type'  => $arctypeInfo,
            'field' => $result,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        /*模板文件*/
        $tempview = !empty($result['tempview']) ? $result['tempview'] : 'view_'.$this->nid.'.'.$this->view_suffix;
        /*--end*/

        $fetch_tpl = 'template/'.$this->theme_style.'/'.$tempview;
        return $this->fetch($fetch_tpl);
    }

    /**
     * 下载文件
     */
    public function downfile()
    {
        $file_id = I('param.id/d', 0);
        $uhash = I('param.uhash/s', '');

        if (empty($file_id) || empty($uhash)) {
            $this->error('下载地址出错！');
            exit;
        }

        $map = array(
            'file_id'   => $file_id,
            'uhash' => $uhash,
        );
        $result = M('download_file')->where($map)->find();
        $filename = isset($result['file_url']) ? trim($result['file_url'], '/') : '';
        $filename = ROOT_PATH.$filename;
        clearstatcache();
        if (empty($result) || !is_file($filename)) {
            $this->error('下载文件不存在！');
            exit;
        }
        $file_url = is_http_url($result['file_url']) ? $result['file_url'] : ROOT_PATH.trim($result['file_url'], '/');
        if (md5_file($file_url) != $result['md5file']) {
            $this->error('下载文件包不完整！');
            exit;
        }
        
        header('Location: '. $result['file_url']);
        exit;
    }
}