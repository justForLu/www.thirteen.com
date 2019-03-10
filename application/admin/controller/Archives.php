<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;

use think\Db;
use think\Page;
use app\common\logic\ArctypeLogic;

class Archives extends Base
{
    // 允许发布文档的模型ID
    public $allowReleaseChannel = array();
    
    public function _initialize() {
        parent::_initialize();
        $this->allowReleaseChannel = config('global.allow_release_channel');
    }

    /**
     * 内容管理
     */
    public function index()
    {
        $arctype_list = array();
        // 目录列表
        $arctypeLogic = new ArctypeLogic(); 
        $arctype_list = $arctypeLogic->arctype_list(0, 0, false, 0, array(), false);
        $zNodes = "[";
        foreach ($arctype_list as $key => $val) {
            $current_channel = $val['current_channel'];
            if (6 == $current_channel) {
                $gourl = url('Arctype/single_edit', array('typeid'=>$val['id']));
                $typeurl = url("Arctype/single_edit", array('typeid'=>$val['id'],'gourl'=>$gourl));
            } else if (8 == $current_channel) {
                $typeurl = url("Guestbook/index", array('typeid'=>$val['id']));
            } else {
                $typeurl = url('Archives/index_archives', array('typeid'=>$val['id']));
            }
            $typename = $val['typename'];
            $zNodes .= "{"."id:{$val['id']}, pId:{$val['parent_id']}, name:\"{$typename}\", url:'{$typeurl}',target:'content_body'";
            /*默认展开一级栏目*/
            if (empty($val['parent_id'])) {
                $zNodes .= ",open:true";
            }
            /*--end*/
            /*栏目有下级栏目时，显示图标*/
            if (1 == $val['has_children']) {
                $zNodes .= ",isParent:true";
            } else {
                $zNodes .= ",isParent:false";
            }
            /*--end*/
            $zNodes .= "},";
        }
        $zNodes .= "]";
        $this->assign('zNodes', $zNodes);

        return $this->fetch();
    }

    /**
     * 内容管理 - 所有文档列表风格（只针对ey_archives表，排除单页记录）
     */
    public function index_archives()
    {
        $assign_data = array();
        $condition = array();
        // 获取到所有URL参数
        $param = I('param.');
        $typeid = I('typeid/d', 0);

        /*跳转到指定栏目的文档列表*/
        if (0 < intval($typeid)) {
            $row = db('arctype')
                ->alias('a')
                ->field('b.ctl_name,b.id')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            $ctl_name = $row['ctl_name'];
            $current_channel = $row['id'];
            if (6 == $current_channel) {
                $gourl = url('Arctype/single_edit', array('typeid'=>$typeid));
                $gourl = url("Arctype/single_edit", array('typeid'=>$typeid,'gourl'=>$gourl));
                $this->redirect($gourl);
            } else if (8 == $current_channel) {
                $gourl = url("Guestbook/index", array('typeid'=>$typeid));
                $this->redirect($gourl);
            }
        }
        /*--end*/

        // 应用搜索条件
        foreach (['keywords','typeid'] as $key) {
            if (isset($param[$key]) && $param[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$param[$key]}%");
                } else if ($key == 'typeid') {
                    $typeid = $param[$key];
                    $hasRow = model('Arctype')->getHasChildren($typeid);
                    $typeids = get_arr_column($hasRow, 'id');
                    /*权限控制 by 小虎哥*/
                    $admin_info = session('admin_info');
                    if (0 < intval($admin_info['role_id'])) {
                        $auth_role_info = $admin_info['auth_role_info'];
                        if(! empty($auth_role_info)){
                            if(isset($auth_role_info['only_oneself']) && 1 == $auth_role_info['only_oneself']){
                                $condition['a.admin_id'] = $admin_info['admin_id'];
                            }
                            if(! empty($auth_role_info['permission']['arctype'])){
                                if (!empty($typeid)) {
                                    $typeids = array_intersect($typeids, $auth_role_info['permission']['arctype']);
                                }
                            }
                        }
                    }
                    /*--end*/
                    $condition['a.typeid'] = array('IN', $typeids);
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }
        
        /*权限控制 by 小虎哥*/
        if (empty($typeid)) {
            $typeids = [];
            $admin_info = session('admin_info');
            if (0 < intval($admin_info['role_id'])) {
                $auth_role_info = $admin_info['auth_role_info'];
                if(! empty($auth_role_info)){
                    if(isset($auth_role_info['only_oneself']) && 1 == $auth_role_info['only_oneself']){
                        $condition['a.admin_id'] = $admin_info['admin_id'];
                    }
                    if(! empty($auth_role_info['permission']['arctype'])){
                        $typeids = $auth_role_info['permission']['arctype'];
                    }
                }
            }
            if (!empty($typeids)) {
                $condition['a.typeid'] = array('IN', $typeids); 
            }
        }
        /*--end*/

        if (empty($typeid)) {
            // 只显示允许发布文档的模型，且是开启状态
            $channelIds = Db::name('channeltype')->where('status',0)
                ->whereOr('id','IN',[6,8])->column('id');
            $condition['a.channel'] = array('NOT IN', $channelIds);
        } else {
            // 只显示当前栏目对应模型下的文档
            $current_channel = Db::name('arctype')->where('id',$typeid)->getField('current_channel');
            $condition['a.channel'] = array('eq', $current_channel);
        }

        /**
         * 数据查询，搜索出主键ID的值
         */
        $count = DB::name('archives')->alias('a')->where($condition)->count('aid');// 查询满足要求的总记录数
        $Page = new Page($count, config('paginate.list_rows'));// 实例化分页类 传入总记录数和每页显示的记录数
        $list = DB::name('archives')
            ->field("a.aid,a.channel")
            ->alias('a')
            ->where($condition)
            ->order('a.aid desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->getAllWithIndex('aid');

        /**
         * 完善数据集信息
         * 在数据量大的情况下，经过优化的搜索逻辑，先搜索出主键ID，再通过ID将其他信息补充完整；
         */
        if ($list) {
            $aids = array_keys($list);
            $fields = "b.*, a.*, a.aid as aid";
            $row = DB::name('archives')
                ->field($fields)
                ->alias('a')
                ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
                ->where('a.aid', 'in', $aids)
                ->getAllWithIndex('aid');

            /*获取当页文档的所有模型*/
            $channelIds = get_arr_column($list, 'channel');
            $channelRow = Db::name('channeltype')->field('id, ctl_name')
                ->where('id','IN',$channelIds)
                ->getAllWithIndex('id');
            $assign_data['channelRow'] = $channelRow;
            /*--end*/

            foreach ($list as $key => $val) {
                $row[$val['aid']]['arcurl'] = get_arcurl($row[$val['aid']]);
                $list[$key] = $row[$val['aid']];
            }
        }
        $show = $Page->show(); // 分页显示输出
        $assign_data['page'] = $show; // 赋值分页输出
        $assign_data['list'] = $list; // 赋值数据集
        $assign_data['pager'] = $Page; // 赋值分页对象

        // 栏目ID
        $assign_data['typeid'] = $typeid; // 栏目ID
        /*当前栏目信息*/
        $arctype_info = array();
        if ($typeid > 0) {
            $arctype_info = M('arctype')->field('typename')->find($typeid);
        }
        $assign_data['arctype_info'] = $arctype_info;
        /*--end*/

        /*允许发布文档列表的栏目*/
        $assign_data['arctype_html'] = allow_release_arctype($typeid, array());
        /*--end*/
        
        /*返回上一层链接*/
        $gourl = U('Archives/index_archives', array('typeid'=>$typeid));
        $assign_data['gourl'] = $gourl;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch('index_archives');
    }

    /**
     * 内容管理 - 栏目展开风格
     */
    private function index_arctype() {
        $arctype_list = array();
        // 目录列表
        $arctypeLogic = new ArctypeLogic(); 
        $arctype_list = $arctypeLogic->arctype_list(0, 0, false, 0, array(), false);
        $this->assign('arctype_list', $arctype_list);

        // 模型列表
        $channeltype_list = getChanneltypeList();
        $this->assign('channeltype_list', $channeltype_list);

        // 栏目最多级别
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $this->assign('arctype_max_level', $arctype_max_level);

        // 允许发布文档的模型
        $this->assign('allow_release_channel', $this->allowReleaseChannel);

        return $this->fetch('index_arctype');
    }

    /**
     * 发布文档
     */
    public function add()
    {
        $typeid = I('param.typeid/d', 0);
        if (!empty($typeid)) {
            $row = db('arctype')
                ->alias('a')
                ->field('b.ctl_name,b.id')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            $gourl = U('Archives/index_archives', array('typeid'=>$typeid));
            $jumpUrl = U("{$row['ctl_name']}/add", array('typeid'=>$typeid,'gourl'=>$gourl));
        } else {
            $jumpUrl = U("Archives/release");
        }
        $this->redirect($jumpUrl);
    }

    /**
     * 编辑文档
     */
/*    public function edit()
    {
        $id = I('param.id/d', 0);
        $typeid = I('param.typeid/d', 0);
        $row = db('archives')
            ->alias('a')
            ->field('b.ctl_name,b.id')
            ->join('__CHANNELTYPE__ b', 'a.channel = b.id', 'LEFT')
            ->where('a.aid', 'eq', $id)
            ->find();
        if (empty($row['channel'])) {
            $channelRow = Db::name('channeltype')->field('id as channel, ctl_name')
                ->where('id',1)
                ->find();
            $row = array_merge($row, $channelRow);
        }
        $gourl = U('Archives/index_archives', array('typeid'=>$typeid));
        $jumpUrl = U("{$row['ctl_name']}/edit", array('id'=>$id,'gourl'=>$gourl));
        $this->redirect($jumpUrl);
    }*/

    /**
     * 删除文档
     */
    public function del()
    {
        $archivesLogic = new \app\admin\logic\ArchivesLogic;
        $archivesLogic->del();
    }
    
    /**
     * 移动
     */
    public function move()
    {
        if (IS_POST) {
            $post = I('post.');
            $typeid = !empty($post['typeid']) ? eyIntval($post['typeid']) : '';
            $aids = !empty($post['aids']) ? eyIntval($post['aids']) : '';

            if (empty($typeid) || empty($aids)) {
                respose(array('status'=>0, 'msg'=>'参数有误'));
            }

            $update_data = array(
                'typeid'    => $typeid,
                'update_time'   => getTime(),
            );
            $r = M('archives')->where("aid in ($aids)")->update($update_data);
            if($r){
                adminLog('移动文档-id：'.$aids);
                respose(array('status'=>1, 'msg'=>'操作成功'));
            }else{
                respose(array('status'=>0, 'msg'=>'操作失败'));
            }
        }

        $typeid = I('param.typeid/d', 0);

        /*允许发布文档列表的栏目*/
        $allowReleaseChannel = [];
        if (!empty($typeid)) {
            $channelId = Db::name('arctype')->where('id',$typeid)->getField('current_channel');
            $allowReleaseChannel[] = $channelId;
        }
        $arctype_html = allow_release_arctype($typeid, $allowReleaseChannel);
        $this->assign('arctype_html', $arctype_html);
        /*--end*/

        /*不允许发布文档的模型ID，用于JS判断*/
        $js_allow_channel_arr = '[';
        foreach ($allowReleaseChannel as $key => $val) {
            if ($key > 0) {
                $js_allow_channel_arr .= ',';
            }
            $js_allow_channel_arr .= $val;
        }
        $js_allow_channel_arr = $js_allow_channel_arr.']';
        $this->assign('js_allow_channel_arr', $js_allow_channel_arr);
        /*--end*/

        /*表单提交URL*/
        $form_action = url('Archives/move');
        $this->assign('form_action', $form_action);
        /*--end*/

        return $this->fetch();
    }

    /**
     * 发布内容
     */
    public function release()
    {
        $typeid = I('param.typeid/d', 0);
        if (0 < $typeid) {
            $param = I('param.');
            $row = db('arctype')
                ->field('b.ctl_name,b.id')
                ->alias('a')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where('a.id', 'eq', $typeid)
                ->find();
            /*针对不支持发布文档的模型*/
            if (!in_array($row['id'], $this->allowReleaseChannel)) {
                $this->error('该栏目不支持发布文档！', U('Archives/release'));
                exit;
            }
            /*-----end*/

            $gourl = U('Archives/index_archives', array('typeid'=>$typeid));
            $jumpUrl = U("{$row['ctl_name']}/add", array('typeid'=>$typeid,'gourl'=>$gourl));
            header('Location: '.$jumpUrl);
            exit;
        }

        $iframe = I('param.iframe/d',0);

        /*允许发布文档列表的栏目*/
        $select_html = allow_release_arctype();
        $this->assign('select_html',$select_html);
        /*--end*/

        /*不允许发布文档的模型ID，用于JS判断*/
        $js_allow_channel_arr = '[';
        foreach ($this->allowReleaseChannel as $key => $val) {
            if ($key > 0) {
                $js_allow_channel_arr .= ',';
            }
            $js_allow_channel_arr .= $val;
        }
        $js_allow_channel_arr = $js_allow_channel_arr.']';
        $this->assign('js_allow_channel_arr', $js_allow_channel_arr);
        /*--end*/

        $this->assign('iframe', $iframe);
        $template = !empty($iframe) ? 'release_iframe' : 'release';

        return $this->fetch($template);
    }

    public function ajax_get_arctype()
    {
        $pid = I('pid/d');
        $html = '';
        $status = 0;
        if (0 < $pid) {
            $map = array(
                'current_channel'    => array('IN', $this->allowReleaseChannel),
                'parent_id' => $pid,
            );
            $row = model('Arctype')->getAll('id,typename', $map, 'id');
            if (!empty($row)) {
                $status = 1;
                $html = '<option value="0">请选择栏目…</option>';
                foreach ($row as $key => $val) {
                    $html .= '<option value="'.$val['id'].'">'.$val['typename'].'</option>';
                }
            }
        }

        respose(array(
            'status'    => $status,
            'msg'   => $html,
        ));
    }
}