<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;

use think\Page;
use think\Db;
use app\common\logic\ArctypeLogic;
use app\admin\logic\FieldLogic;

class Arctype extends Base
{
    public $fieldLogic;
    // 栏目对应模型ID
    public $arctype_channel_id = '';
    // 允许发布文档的模型ID
    public $allowReleaseChannel = array();
    // 禁用的目录名称
    public $disableDirname = array('application','core','data','extend','html','install','public','plugins','upload','template','vendor','weapp','tags','search');
    
    public function _initialize() {
        parent::_initialize();
        $this->fieldLogic = new FieldLogic();
        $this->allowReleaseChannel = config('global.allow_release_channel');
        $this->arctype_channel_id = config('global.arctype_channel_id');
    }

    public function index()
    {
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

        return $this->fetch();
    }
    
    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $post = I('post.');
            if ($post) {
                /*目录名称*/
                $dirname = $this->get_dirpinyin($post['typename'], $post['dirname']);
                $dirname = preg_replace('/(\s)+/', '_', $dirname);
                if (in_array(strtolower($dirname), $this->disableDirname)) {
                    $this->error('目录名称与系统内置重名，请更改！');
                }
                /*--end*/
                $dirpath = rtrim($post['dirpath'],'/');
                /* ------临时代码，当能支持静态页面生成，再去掉 */
                $dirpath = $dirpath . '/' . $dirname;
                /* -----------end----------- */
                $typelink = !empty($post['is_part']) ? $post['typelink'] : '';
                /*封面图的本地/远程图片处理*/
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                /*--end*/
                // 获取顶级模型ID
                if (empty($post['parent_id'])) {
                    $channeltype = $post['current_channel'];
                } else {
                    $channeltype = M('arctype')->where('id', $post['parent_id'])->getField('channeltype');
                }
                /*SEO描述*/
                $seo_description = $post['seo_description'];
                $seo_description = @msubstr($seo_description, 0, 500, false);
                /*--end*/
                /*处理自定义字段值*/
                $addonField = array();
                if (!empty($post['addonField'])) {
                    $addonField = $this->fieldLogic->handleAddonField($this->arctype_channel_id, $post['addonField']);
                }
                /*--end*/
                $newData = array(
                    'dirname' => $dirname,
                    'dirpath'   => $dirpath,
                    'typelink' => $typelink,
                    'litpic'    => $litpic,
                    'channeltype'   => $channeltype,
                    'current_channel' => $post['current_channel'],
                    'seo_keywords' => str_replace('，', ',', $post['seo_keywords']),
                    'seo_description' => $seo_description,
                    'admin_id'  => session('admin_info.admin_id'),
                    'add_time'  => getTime(),
                    'update_time'  => getTime(),
                );
                $data = array_merge($post, $newData, $addonField);
                $insertId = M('arctype')->insertGetId($data);
                $_POST['id'] = $insertId;
                
                if($insertId){
                    // --存储单页模型
                    if ($data['current_channel'] == 6) {
                        $archivesData = array(
                            'title' => $data['typename'],
                            'typeid'=> $insertId,
                            'channel'   => $data['current_channel'],
                            'sort_order'    => 100,
                            'add_time'  => getTime(),
                        );
                        // $archivesData = array_merge($archivesData, $data);
                        $aid = M('archives')->insertGetId($archivesData);
                        if ($aid) {
                            // ---------后置操作
                            if (!isset($post['addonFieldExt'])) {
                                $post['addonFieldExt'] = array(
                                    'typeid'    => $archivesData['typeid'],
                                );
                            } else {
                                $post['addonFieldExt']['typeid'] = $archivesData['typeid'];
                            }
                            $addData = array(
                                'addonFieldExt' => $post['addonFieldExt'],
                            );
                            $addData = array_merge($addData, $archivesData);
                            model('Single')->afterSave($aid, $addData, 'add');
                            // ---------end
                        }
                    }

                    /*同步栏目ID到权限组，默认是赋予该栏目的权限*/
                    $this->syn_auth_role($insertId);
                    /*--end*/

                    \think\Cache::clear('arctype');
                    extra_cache('admin_all_menu', NULL);
                    \think\Cache::clear('admin_archives_release');
                    adminLog('新增栏目：'.$data['typename']);
                    $this->success("操作成功!",U('Arctype/index'));
                    exit;
                }
            }
            $this->error("操作失败!");
            exit;
        }

        $assign_data = array();

        /* 模型 */
        $map = array(
            'status'    => 1,
        );
        $channeltype_list = model('Channeltype')->getAll('id,title,nid', $map, 'id');
        $this->assign('channeltype_list', $channeltype_list);

        // 新增栏目在指定的上一级栏目下
        $parent_id = I('param.parent_id/d');
        $grade = 0;
        $current_channel = '';
        $predirpath = tpCache('seo.seo_arcdir');
        $ptypename = '';
        if (0 < $parent_id) {
            $info = M('arctype')->where(array('id'=>$parent_id))->find();
            if ($info) {
                // 级别
                $grade = $info['grade'] + 1;
                // 菜单对应下的栏目
                // $selected = $info['id'];
                // 模型
                $current_channel = $info['current_channel'];
                // 上级目录
                $predirpath = $info['dirpath'];
                // 上级栏目名称
                $ptypename = $info['typename'];
            }
        }
        $this->assign('predirpath', $predirpath);
        $this->assign('parent_id', $parent_id);
        $this->assign('ptypename', $ptypename);
        $this->assign('grade',$grade);
        $this->assign('current_channel',$current_channel);
        
        /*发布文档的模型ID，用于是否显示文档模板列表*/
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

        /*模板列表*/
        $templateList = $this->getTemplateList('add');
        $this->assign('templateList', $templateList);
        /*--end*/

        /*自定义字段*/
        $assign_data['addonFieldList'] = model('Field')->getTabelFieldList(-99);
        $assign_data['aid'] = 0;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'arctype';
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        if (IS_POST) {
            $post = I('post.');
            if(!empty($post['id'])){
                /*目录名称*/
                $dirname = $this->get_dirpinyin($post['typename'], $post['dirname'], $post['id']);
                $dirname = preg_replace('/(\s)+/', '_', $dirname);
                if (in_array(strtolower($dirname), $this->disableDirname)) {
                    $this->error('目录名称与系统内置重名，请更改！');
                }
                /*--end*/
                $dirpath = rtrim($post['dirpath'], '/');
                $typelink = !empty($post['is_part']) ? $post['typelink'] : '';
                /*封面图的本地/远程图片处理*/
                $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post['litpic_remote'];
                } else {
                    $litpic = $post['litpic_local'];
                }
                /*--end*/
                // 最顶级模型ID
                $channeltype = $post['channeltype'];
                // 当前更改的等级
                $grade = $post['grade']; 
                // 根据栏目ID获取最新的最顶级模型ID
                if (intval($post['parent_id']) > 0) {
                    $arctype_row = M('arctype')->field('grade,channeltype')->where('id', $post['parent_id'])->find();
                    $channeltype = $arctype_row['channeltype'];
                    $grade = $arctype_row['grade'] + 1;
                }
                /*SEO描述*/
                $seo_description = $post['seo_description'];
                $seo_description = @msubstr($seo_description, 0, 500, false);
                /*--end*/
                // 获取当前栏目的所有子孙栏目（不包含当前栏目）
                $allSonTypeidArr = model('Arctype')->getHasChildren($post['id'], false);

                /*处理自定义字段值*/
                $addonField = array();
                if (!empty($post['addonField'])) {
                    $addonField = $this->fieldLogic->handleAddonField($this->arctype_channel_id, $post['addonField']);
                }
                /*--end*/

                $newData = array(
                    'dirname' => $dirname,
                    'dirpath'   => $dirpath,
                    'typelink' => $typelink,
                    'litpic'    => $litpic,
                    'channeltype'   => $channeltype,
                    'grade' => $grade,
                    'seo_keywords' => str_replace('，', ',', $post['seo_keywords']),
                    'seo_description' => $seo_description,
                    'update_time'  => getTime(),
                );
                $data = array_merge($post, $newData, $addonField);
                $r = M('arctype')->where(array('id'=>$data['id']))->cache(true,null,"arctype")->update($data);
                if($r){
                    /*批量更新所有子孙栏目的最顶级模型ID*/
                    if (!empty($allSonTypeidArr)) {
                        $i = 1;
                        $minuendGrade = 0;
                        foreach ($allSonTypeidArr as $key => $val) {
                            if ($i == 1) {
                                $firstGrade = intval($post['oldgrade']);
                                $minuendGrade = intval($grade) - $firstGrade;
                            }
                            $update_data = array(
                                'channeltype'        => $channeltype,
                                'update_time'        => getTime(),
                                'grade'   =>  Db::raw('grade+'.$minuendGrade),
                            );
                            M('arctype')->where(array('id'=>array('eq', $val['id'])))->cache(true,null,"arctype")->fetchSql(false)->update($update_data);
                            ++$i;
                        }
                    }
                    /*--end*/

                    // --存储单页模型
                    if ($data['current_channel'] == 6) {
                        $archivesData = array(
                            'title' => $data['typename'],
                            'typeid'=> $data['id'],
                            'channel'   => $data['current_channel'],
                            'sort_order'    => 100,
                            'update_time'     => getTime(),
                        );
                        // $archivesData = array_merge($archivesData, $data);
                        $aid = M('single_content')->where(array('typeid'=>$data['id']))->getField('aid');
                        if (empty($aid)) {
                            $opt = 'add';
                            $archivesData['add_time'] = getTime();
                            $up = $aid = M('archives')->insertGetId($archivesData);
                        } else {
                            $opt = 'edit';
                            $up = M('archives')->where(array('aid'=>$aid))->update($archivesData);
                        }
                        if ($up) {
                            // ---------后置操作
                            if (!isset($post['addonFieldExt'])) {
                                $post['addonFieldExt'] = array(
                                    'typeid'    => $data['id'],
                                );
                            } else {
                                $post['addonFieldExt']['typeid'] = $data['id'];
                            }
                            $updateData = array(
                                'addonFieldExt' => $post['addonFieldExt'],
                            );
                            $updateData = array_merge($updateData, $archivesData);
                            model('Single')->afterSave($aid, $updateData, $opt);
                            // ---------end
                        }
                    }

                    \think\Cache::clear('arctype');
                    extra_cache('admin_all_menu', NULL);
                    \think\Cache::clear('admin_archives_release');
                    adminLog('编辑栏目：'.$data['typename']);
                    $this->success("操作成功!", U('Arctype/index'));
                    exit;
                }
            }
            $this->error("操作失败!");
            exit;
        }

        $assign_data = array();

        $id = I('id/d');
        $info = M('arctype')->where(array('id'=>$id))->find();
        if (empty($info)) {
            $this->error('数据不存在，请联系管理员！');
            exit;
        }
        // 栏目图片处理
        if (is_http_url($info['litpic'])) {
            $info['is_remote'] = 1;
            $info['litpic_remote'] = $info['litpic'];
        } else {
            $info['is_remote'] = 0;
            $info['litpic_local'] = $info['litpic'];
        }
        $this->assign('field',$info);

        // 获得上级目录路径
        if (!empty($info['dirpath'])) {
            $predirpath = preg_replace('/\/([^\/]*)$/i', '', $info['dirpath']);
        } else {
            $predirpath = tpCache('seo.seo_arcdir');
        }
        $this->assign('predirpath',$predirpath);

        // 是否有子栏目
        $hasChildren = model('Arctype')->hasChildren($id);
        if ($hasChildren > 0) {
            $select_html = M('arctype')->where('id', $info['parent_id'])->getField('typename');
            $select_html = !empty($select_html) ? $select_html : '顶级栏目';
        } else {
            // 所属栏目
            // $channeltype = $info['channeltype'];
            $select_html = '<option value="0" data-grade="-1" data-dirpath="'.tpCache('seo.seo_arcdir').'">顶级栏目</option>';
            $selected = $info['parent_id'];
            $arctype_max_level = intval(config('global.arctype_max_level'));
            $arctypeLogic = new ArctypeLogic();
            $options = $arctypeLogic->arctype_list(0, $selected, false, $arctype_max_level - 1);
            foreach ($options AS $var)
            {
                $select_html .= '<option value="' . $var['id'] . '" data-grade="' . $var['grade'] . '" data-dirpath="'.$var['dirpath'].'"';
                $select_html .= ($selected == $var['id']) ? "selected='ture'" : '';
                $select_html .= '>';
                if ($var['level'] > 0)
                {
                    $select_html .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select_html .= htmlspecialchars(addslashes($var['typename'])) . '</option>';
            }
        }
        $this->assign('select_html',$select_html);
        $this->assign('hasChildren',$hasChildren);

        /* 模型 */
        $map = array(
            'status'    => 1,
        );
        $channeltype_list = model('Channeltype')->getAll('id,title,nid,ctl_name', $map, 'id');
        // 模型对应模板文件不存在报错
        if (!isset($channeltype_list[$info['current_channel']])) {
            $row = model('Channeltype')->getInfo($info['current_channel']);
            $file = 'lists_'.$row['nid'].'.htm';
            $this->error($row['title'].'缺少模板文件'.$file);
        }
        // 选项卡内容的链接
        $ctl_name = $channeltype_list[$info['current_channel']]['ctl_name'];
        $list_url = U("{$ctl_name}/index")."?typeid={$id}";
        $this->assign('list_url', $list_url);
        $this->assign('channeltype_list', $channeltype_list);
        
        /*发布文档的模型ID，用于是否显示文档模板列表*/
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

        /*选项卡*/
        $tab = I('param.tab/d', 1);
        $this->assign('tab', $tab);
        /*--end*/

        /*模板列表*/
        $templateList = $this->getTemplateList('edit', $info['templist'], $info['tempview']);
        $this->assign('templateList', $templateList);
        /*--end*/

        /*自定义字段*/
        $assign_data['addonFieldList'] = model('Field')->getTabelFieldList(-99, $id);
        $assign_data['aid'] = $id;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'arctype';
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 内容管理
     */
    public function single_edit()
    {
        if (IS_POST) {
            $post = I('post.');
            $typeid = I('post.typeid/d', 0);
            if(!empty($typeid)){
                $info = M('arctype')->field('typename')->where(array('id'=>$typeid))->find();
                $aid = M('archives')->where(array('typeid'=>$typeid, 'channel'=>6))->getField('aid');
                if (!isset($post['addonFieldExt'])) {
                    $post['addonFieldExt'] = array();
                }
                $updateData = array(
                    'aid'   => $aid,
                    'addonFieldExt' => $post['addonFieldExt'],
                );
                model('Single')->afterSave($aid, $updateData, 'edit');

                \think\Cache::clear('arctype');
                adminLog('编辑栏目：'.$info['typename']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }
            $this->error("操作失败!");
            exit;
        }

        $assign_data = array();

        $typeid = I('typeid/d');
        $info = M('arctype')->where(array('id'=>$typeid))->find();
        if (empty($info)) {
            $this->error('数据不存在，请联系管理员！');
            exit;
        }
        $assign_data['info'] = $info;

        /*自定义字段*/
        $assign_data['addonFieldExtList'] = model('Field')->getChannelFieldList(6, 0, $typeid);
        $assign_data['aid'] = $typeid;
        $assign_data['channeltype'] = 6;
        $assign_data['nid'] = 'single';
        /*--end*/

        /*返回上一层*/
        $gourl = I('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = U('Arctype/index');
        }
        $assign_data['gourl'] = $gourl;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 删除
     */
    public function del()
    {
        $post = I('post.');
        $post['del_id'] = eyIntval($post['del_id']);

        /*当前栏目信息*/
        $row = M('arctype')->field('id, current_channel, typename')->find($post['del_id']);
        
        $r = model('arctype')->del($post['del_id']);
        if (false !== $r) {
            // ---------后置操作
            model('arctype')->del($post['del_id']);
            // ---------end
            adminLog('删除栏目：'.$row['typename']);
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 获取栏目的拼音，确保唯一性
     */
    private function get_dirpinyin($typename = '', $dirname = '', $id = 0)
    {
        if (empty($dirname)) {
            $dirname = get_pinyin($typename);
        }
        if (strval(intval($dirname)) == strval($dirname)) {
            $dirname .= get_rand_str(3,0,0);
        }
        $map = array(
            'dirname'   => $dirname,
        );
        if (intval($id) > 0) {
            $map['id']  = array('neq', $id);
        }
        $result = M('arctype')->field('id')->where($map)->find();
        if (!empty($result)) {
            $nowDirname = $dirname.get_rand_str(3,0,0);
            return $this->get_dirpinyin($typename, $nowDirname, $id);
        }

        return $dirname;
    }

    /**
     * 通过模型获取栏目
     */
    public function ajax_get_arctype($channeltype = 0)
    {
        $arctypeLogic = new ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $options = $arctypeLogic->arctype_list(0, 0, false, $arctype_max_level, array('channeltype'=>$channeltype));
        $select_html = '<option value="0" data-grade="-1">顶级栏目</option>';
        foreach ($options AS $var)
        {
            $select_html .= '<option value="' . $var['id'] . '" data-grade="' . $var['grade'] . '" data-dirpath="'.$var['dirpath'].'"';
            $select_html .= '>';
            if ($var['level'] > 0)
            {
                $select_html .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select_html .= htmlspecialchars(addslashes($var['typename'])) . '</option>';
        }

        $returndata = array(
            'status' => 1,
            'select_html' => $select_html,
        );
        
        respose($returndata);
    }

    /**
     * 获取栏目的拼音
     */
    public function ajax_get_dirpinyin($typename = '')
    {
        $typename = I('post.typename/s');
        $pinyin = get_pinyin($typename);

        respose(array(
            'status'    => 1,
            'msg'   => $pinyin
        ));
    }

    /**
     * 检测文件保存目录是否存在
     */
    public function ajax_check_dirpath()
    {
        $dirpath = I('post.dirpath/s');
        $id = I('post.id/d');
        $map = array(
            'dirpath' => $dirpath,
        );
        if (intval($id) > 0) {
            $map['id'] = array('neq', $id);
        }
        $result = M('arctype')->where($map)->find();
        if (!empty($result)) {
            respose(array(
                'status'    => 0,
                'msg'   => '文件保存目录已存在，请更改',
            ));
        } else {
            respose(array(
                'status'    => 1,
                'msg'   => '文件保存目录可用',
            ));
        }
    }

    private function getTemplateList($opt = 'add', $templist = '', $tempview = '')
    {   
        $planPath = ROOT_PATH.'template/pc';
        $dirRes   = opendir($planPath);
        $view_suffix = config('template.view_suffix');

        /*模板PC目录文件列表*/
        $templateArr = array();
        while($filename = readdir($dirRes))
        {
            if (in_array($filename, array('.','..'))) {
                continue;
            }
            array_push($templateArr, $filename);
        }
        /*--end*/

        $templateList = array();
        $channelList = model('Channeltype')->getAll();
        foreach ($channelList as $k1 => $v1) {
            $l = 1;
            $v = 1;
            $lists = ''; // 销毁列表模板
            $view = ''; // 销毁文档模板
            $templateList[$v1['id']] = array();
            foreach ($templateArr as $k2 => $v2) {
                if ('add' == $opt) {
                    $selected = 0; // 默认选中状态
                } else {
                    $selected = 1; // 默认选中状态
                }
                preg_match('/^(lists|view)_'.$v1['nid'].'(_(\w)+)?\.'.$view_suffix.'/i', $v2, $matches);
                if (!empty($matches)) {
                    $selectefile = '';
                    if ('lists' == $matches[1]) {
                        $lists .= '<option value="'.$v2.'" ';
                        $lists .= ($templist == $v2 || $selected == $l) ? " selected='true' " : '';
                        $lists .= '>'.$v2.'</option>';
                        $l++;
                    } else if ('view' == $matches[1]) {
                        $view .= '<option value="'.$v2.'" ';
                        $view .= ($tempview == $v2 || $selected == $v) ? " selected='true' " : '';
                        $view .= '>'.$v2.'</option>';
                        $v++;
                    }
                }
            }
            if ('add' == $opt) {
                $lists = !empty($lists) ? $lists : '<option value="">无</option>';
                $view = !empty($view) ? $view : '<option value="">无</option>';
            } else {
                $lists = '<option value="">请选择模板…</option>'.$lists;
                $view = '<option value="">请选择模板…</option>'.$view;
            }
            $templateList[$v1['id']] = array(
                'lists' => $lists,
                'view' => $view,
            );
        }

        return $templateList;
    }

    public function ajax_get_template()
    {
        $id = I('param.id/d', 0);
        $channel = I('param.channel/d', 0);
        $selected_templist = I('param.sltd_templist/s', '');;
        $selected_tempview = I('param.sltd_tempview/s', '');;

        $status = 1;
        $templisthtml = '<option value="">无</option>';
        $tempviewhtml = '<option value="">无</option>';

        if ($channel > 0) {
            $nid = M('Channeltype')->where('id', $channel)->getField('nid');
            if (!empty($nid)) {
                $planPath = 'template/pc';
                $planPath = realpath($planPath);
                $dirRes   = opendir($planPath);
                $view_suffix = config('template.view_suffix');
                if ($id > 0) { //编辑状态
                    $selected = 1;
                    $templisthtml = '<option value="">请选择模板…</option>';
                    $tempviewhtml = '<option value="">请选择模板…</option>';
                } else { // 新增状态
                    $selected = 0;
                    $templisthtml = '';
                    $tempviewhtml = '';
                }

                $i = 1;
                $j = 1;
                while($filename = readdir($dirRes))
                {
                    if(preg_match('/^lists_'.$nid.'(_(\w)+)?\.'.$view_suffix.'/i', $filename) == 1)
                    {
                        /*列表模板*/
                        $templisthtml .= '<option value="'.$filename.'" ';
                        $templisthtml .= ($selected_templist == $filename || $selected == $i) ? " selected='true' " : '';
                        $templisthtml .= '>'.$filename.'</option>';
                        /*--end*/
                        $i++;
                    } 
                    elseif(preg_match('/^view_'.$nid.'(_(\w)+)?\.'.$view_suffix.'/i', $filename) == 1) 
                    {
                        /*文档模板*/
                        $tempviewhtml .= '<option value="'.$filename.'" ';
                        $tempviewhtml .= ($selected_tempview == $filename || $selected == $j) ? " selected='true' " : '';
                        $tempviewhtml .= '>'.$filename.'</option>';
                        /*--end*/
                        $j++;
                    }
                }
                if ($i == 1) {
                    $templisthtml = '<option value="">无</option>';
                }
                if ($j == 1) {
                    $tempviewhtml = '<option value="">无</option>';
                }
            }
        }

        respose(array(
            'status'    => $status,
            'templisthtml'   => $templisthtml,
            'tempviewhtml'   => $tempviewhtml,
        ));
    }

    /**
     * 同步栏目ID到权限组，默认是赋予该栏目的权限
     * @param int $typeid
     */
    private function syn_auth_role($typeid = 0)
    {
        if (0 < intval($typeid)) {
            $authRole = new \app\admin\model\AuthRole;
            $roleRow = $authRole->getRoleAll();
            if (!empty($roleRow)) {
                $saveData = [];
                foreach ($roleRow as $key => $val) {
                    $permission = $val['permission'];
                    $rules = !empty($permission['rules']) ? $permission['rules'] : [];
                    if (!in_array(1, $rules)) {
                        continue;
                    }
                    $arctype = !empty($permission['arctype']) ? $permission['arctype'] : [];
                    if (!empty($arctype)) {
                        array_push($arctype, $typeid);
                        $permission['arctype'] = $arctype;
                    }
                    $saveData[] = array(
                        'id'    => $val['id'],
                        'permission'    => $permission,
                    );
                }
                $r = $authRole->saveAll($saveData);
                if (false != $r && -1 < session('admin_info.role_id')) {
                    /*及时更新当前管理员权限*/
                    $auth_role_info = model('AuthRole')->getRole(array('id' => session('admin_info.role_id')));
                    session('admin_info.auth_role_info', $auth_role_info);
                    /*--end*/
                }
            }
        }
    }
}