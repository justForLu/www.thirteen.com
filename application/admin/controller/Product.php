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
use app\admin\logic\ProductLogic;

class Product extends Base
{
    // 模型标识
    public $nid = 'product';
    // 模型ID
    public $channeltype = '';
    // 表单类型
    public $attrInputTypeArr = array();
    
    public function _initialize() {
        parent::_initialize();
        $channeltype_list = config('global.channeltype_list');
        $this->channeltype = $channeltype_list[$this->nid];
        $this->attrInputTypeArr = config('global.attr_input_type_arr');
        $this->assign('nid', $this->nid);
        $this->assign('channeltype', $this->channeltype);
    }

    /**
     * 文章列表
     */
    public function index()
    {
        $assign_data = array();
        $condition = array();
        // 获取到所有GET参数
        $param = I('param.');
        $flag = I('flag/s');
        $typeid = I('typeid/d', 0);
        $begin = strtotime(I('add_time_begin'));
        $end = strtotime(I('add_time_end'));

        // 应用搜索条件
        foreach (['keywords','typeid','flag'] as $key) {
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
                } else if ($key == 'flag') {
                    $condition['a.'.$param[$key]] = array('eq', 1);
                } else {
                    $condition['a.'.$key] = array('eq', $param[$key]);
                }
            }
        }

        // 时间检索
        if ($begin > 0 && $end > 0) {
            $condition['a.add_time'] = array('between',"$begin,$end");
        } else if ($begin > 0) {
            $condition['a.add_time'] = array('egt', $begin);
        } else if ($end > 0) {
            $condition['a.add_time'] = array('elt', $end);
        }

        // 模型ID
        $condition['a.channel'] = array('eq', $this->channeltype);

        /**
         * 数据查询，搜索出主键ID的值
         */
        $count = DB::name('archives')->alias('a')->where($condition)->count();// 查询满足要求的总记录数
        $Page = new Page($count, config('paginate.list_rows'));// 实例化分页类 传入总记录数和每页显示的记录数
        $list = DB::name('archives')
            ->field("a.aid")
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
            foreach ($list as $key => $val) {
                $row[$val['aid']]['arcurl'] = get_arcurl($row[$val['aid']]);
                $list[$key] = $row[$val['aid']];
            }
        }
        $show = $Page->show(); // 分页显示输出
        $assign_data['page'] = $show; // 赋值分页输出
        $assign_data['list'] = $list; // 赋值数据集
        $assign_data['pager'] = $Page; // 赋值分页对象

        /*获取当前模型栏目*/
/*        $selected = 0;
        if ($typeid > 0) {
            $selected = $typeid;
        }
        $arctypeLogic = new ArctypeLogic();
        $map = array(
            'channeltype'    => $this->channeltype,
        );
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $select_html = $arctypeLogic->arctype_list(0, $selected, true, $arctype_max_level, $map);
        $this->assign('select_html',$select_html);*/
        /*--end*/

        // 栏目ID
        $assign_data['typeid'] = $typeid; // 栏目ID
        /*当前栏目信息*/
        $arctype_info = array();
        if ($typeid > 0) {
            $arctype_info = M('arctype')->field('typename')->find($typeid);
        }
        $assign_data['arctype_info'] = $arctype_info;
        /*--end*/
        
        /*选项卡*/
        $tab = I('param.tab/d', 3);
        $assign_data['tab'] = $tab;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if (IS_POST) {
            $post = I('post.');
            $content = I('post.addonFieldExt.content', '', null);

            // 根据标题自动提取相关的关键字
            $seo_keywords = $post['seo_keywords'];
            // if (empty($seo_keywords)) {
            //     $seo_keywords = get_split_word($post['title'], $content);
            // }

            // 自动获取内容第一张图片作为封面图
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            $post['litpic'] = $litpic;

            // SEO描述
            $seo_description = '';
            if (empty($post['seo_description']) && !empty($content)) {
                $seo_description = @msubstr(checkStrHtml($content), 0, 500, false);
            } else {
                $seo_description = $post['seo_description'];
            }

            // --外部链接
            $jumplinks = '';
            $is_jump = isset($post['is_jump']) ? $post['is_jump'] : 0;
            if (intval($is_jump) > 0) {
                $jumplinks = $post['jumplinks'];
            }
            // --存储数据
            $newData = array(
                'typeid'=> empty($post['typeid']) ? 0 : $post['typeid'],
                'channel'   => $this->channeltype,
                'is_b'      => empty($post['is_b']) ? 0 : $post['is_b'],
                'is_head'      => empty($post['is_head']) ? 0 : $post['is_head'],
                'is_special'      => empty($post['is_special']) ? 0 : $post['is_special'],
                'is_recom'      => empty($post['is_recom']) ? 0 : $post['is_recom'],
                'is_jump'     => $is_jump,
                'jumplinks'     => $jumplinks,
                'seo_keywords'     => $seo_keywords,
                'seo_description'     => $seo_description,
                'admin_id'  => session('admin_info.admin_id'),
                'add_time'     => strtotime($post['add_time']),
                'update_time'  => getTime(),
            );
            $data = array_merge($post, $newData);

            $aid = M('archives')->insertGetId($data);
            $_POST['aid'] = $aid;
            if ($aid) {
            	// ---------后置操作
            	model('Product')->afterSave($aid, $data, 'add');
            	// ---------end
                adminLog('新增产品：'.$data['title']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }

            $this->error("操作失败!");
            exit;
        }

        $typeid = I('param.typeid/d', 0);
        $assign_data['typeid'] = $typeid; // 栏目ID
        
        /*允许发布文档列表的栏目*/
        $arctype_html = allow_release_arctype($typeid, array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        /*自定义字段*/
        $assign_data['addonFieldExtList'] = model('Field')->getChannelFieldList($this->channeltype);
        $assign_data['aid'] = 0;
        /*--end*/

        // 阅读权限
        $arcrank_list = get_arcrank_list();
        $assign_data['arcrank_list'] = $arcrank_list;

        /*产品参数*/
        $assign_data['canshu'] = $this->ajax_get_attr_input($typeid);
        /*--end*/

        /*返回上一层*/
        $gourl = I('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = U('Product/index', array('typeid'=>$typeid));
        }
        $assign_data['gourl'] = $gourl;
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
            $content = I('post.addonFieldExt.content', '', null);

            // 根据标题自动提取相关的关键字
            $seo_keywords = $post['seo_keywords'];
            // if (empty($seo_keywords)) {
            //     $seo_keywords = get_split_word($post['title'], $content);
            // }

            // 自动获取内容第一张图片作为封面图
            $is_remote = !empty($post['is_remote']) ? $post['is_remote'] : 0;
            $litpic = '';
            if ($is_remote == 1) {
                $litpic = $post['litpic_remote'];
            } else {
                $litpic = $post['litpic_local'];
            }
            $post['litpic'] = $litpic;

            // 描述
            $seo_description = '';
            if (empty($post['seo_description']) && !empty($content)) {
                $seo_description = @msubstr(checkStrHtml($content), 0, 500, false);
            } else {
                $seo_description = $post['seo_description'];
            }

            // --外部链接
            $jumplinks = '';
            $is_jump = isset($post['is_jump']) ? $post['is_jump'] : 0;
            if (intval($is_jump) > 0) {
                $jumplinks = $post['jumplinks'];
            }
            // --存储数据
            $newData = array(
                'typeid'=> empty($post['typeid']) ? 0 : $post['typeid'],
                'channel'   => $this->channeltype,
                'is_b'      => empty($post['is_b']) ? 0 : $post['is_b'],
                'is_head'      => empty($post['is_head']) ? 0 : $post['is_head'],
                'is_special'      => empty($post['is_special']) ? 0 : $post['is_special'],
                'is_recom'      => empty($post['is_recom']) ? 0 : $post['is_recom'],
                'is_jump'     => $is_jump,
                'jumplinks'     => $jumplinks,
                'seo_keywords'     => $seo_keywords,
                'seo_description'     => $seo_description,
                'add_time'     => strtotime($post['add_time']),
                'update_time'  => getTime(),
            );
            $data = array_merge($post, $newData);

            $r = M('archives')->where(array('aid'=>$data['aid']))->update($data);
            
            if ($r) {
            	// ---------后置操作
            	model('Product')->afterSave($data['aid'], $data, 'edit');
            	// ---------end
                adminLog('编辑产品：'.$data['title']);
                $this->success("操作成功!", $post['gourl']);
                exit;
            }

            $this->error("操作失败!");
            exit;
        }

        $assign_data = array();

        $id = I('id/d');
        $info = model('Product')->getInfo($id);
        if (empty($info)) {
            $this->error('数据不存在，请联系管理员！');
            exit;
        }
        $typeid = $info['typeid'];
        if (is_http_url($info['litpic'])) {
            $info['is_remote'] = 1;
            $info['litpic_remote'] = $info['litpic'];
        } else {
            $info['is_remote'] = 0;
            $info['litpic_local'] = $info['litpic'];
        }
        $assign_data['field'] = $info;

        // 产品相册
        $proimg_list = model('ProductImg')->getProImg($id);
        $assign_data['proimg_list'] = $proimg_list;
        
        /*允许发布文档列表的栏目*/
        $arctype_html = allow_release_arctype($typeid, array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        /*自定义字段*/
        $assign_data['addonFieldExtList'] = model('Field')->getChannelFieldList($info['channel'], 0, $id);
        $assign_data['aid'] = $id;
        /*--end*/

        // 阅读权限
        $arcrank_list = get_arcrank_list();
        $assign_data['arcrank_list'] = $arcrank_list;

        /*产品参数*/
        $assign_data['canshu'] = $this->ajax_get_attr_input($typeid, $id);
        /*--end*/

        /*返回上一层*/
        $gourl = I('param.gourl/s', '');
        if (empty($gourl)) {
            $gourl = U('Product/index', array('typeid'=>$typeid));
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
        $archivesLogic = new \app\admin\logic\ArchivesLogic;
        $archivesLogic->del();
    }

    /**
     * 删除商品相册图
     */
    public function del_proimg()
    {
        $filename= I('filename/s');
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if(eyPreventShell($filename) && !empty($filename) && file_exists($filename)){
            $filetype = preg_replace('/^(.*)\.(\w+)$/i', '$2', $filename);
            $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP文件
            $size = getimagesize($filename);
            $fileInfo = explode('/',$size['mime']);
            if($fileInfo[0] != 'image' || $phpfile || !in_array($filetype, explode(',', config('global.image_ext')))){
                exit;
            }
            M('product_img')->where("image_url = '$filename'")->delete();
        }
    }

    /**
     * 产品参数
     */
    public function attribute_index()
    {
        $assign_data = array();
        $condition = array();
        // 获取到所有GET参数
        $get = I('get.');
        $typeid = I('typeid/d', 0);

        // 应用搜索条件
        foreach (['keywords','typeid'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.attr_name'] = array('LIKE', "%{$get[$key]}%");
                } else if ($key == 'typeid') {
                    $condition['a.typeid'] = array('eq', $get[$key]);
                } else {
                    $condition['a.'.$key] = array('eq', $get[$key]);
                }
            }
        }

        /**
         * 数据查询，搜索出主键ID的值
         */
        $count = DB::name('product_attribute')->alias('a')->where($condition)->count();// 查询满足要求的总记录数
        $Page = new Page($count, config('paginate.list_rows'));// 实例化分页类 传入总记录数和每页显示的记录数
        $list = DB::name('product_attribute')
            ->field("a.attr_id")
            ->alias('a')
            ->where($condition)
            ->order('a.sort_order asc, a.attr_id asc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->getAllWithIndex('attr_id');

        /**
         * 完善数据集信息
         * 在数据量大的情况下，经过优化的搜索逻辑，先搜索出主键ID，再通过ID将其他信息补充完整；
         */
        if ($list) {
            $attr_ida = array_keys($list);
            $fields = "b.*, a.*";
            $row = DB::name('product_attribute')
                ->field($fields)
                ->alias('a')
                ->join('__ARCTYPE__ b', 'a.typeid = b.id', 'LEFT')
                ->where('a.attr_id', 'in', $attr_ida)
                ->getAllWithIndex('attr_id');
            foreach ($row as $key => $val) {
                $val['fieldname'] = 'attr_'.$val['attr_id'];
                $row[$key] = $val;
            }
            foreach ($list as $key => $val) {
                $list[$key] = $row[$val['attr_id']];
            }
        }
        $show = $Page->show(); // 分页显示输出
        $assign_data['page'] = $show; // 赋值分页输出
        $assign_data['list'] = $list; // 赋值数据集
        $assign_data['pager'] = $Page; // 赋值分页对象

        /*获取当前模型栏目*/
        $selected = $typeid;
        $arctypeLogic = new ArctypeLogic();
        $map = array(
            'channeltype'    => $this->channeltype,
        );
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $select_html = $arctypeLogic->arctype_list(0, $selected, true, $arctype_max_level, $map);
        $this->assign('select_html',$select_html);
        /*--end*/

        // 栏目ID
        $assign_data['typeid'] = $typeid; // 栏目ID
        /*当前栏目信息*/
        $arctype_info = array();
        if ($typeid > 0) {
            $arctype_info = M('arctype')->field('typename')->find($typeid);
        }
        $assign_data['arctype_info'] = $arctype_info;
        /*--end*/
        
        /*选项卡*/
        $tab = I('param.tab/d', 3);
        $assign_data['tab'] = $tab;
        /*--end*/
        
        $assign_data['attrInputTypeArr'] = $this->attrInputTypeArr; // 表单类型

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 新增产品参数
     */
    public function attribute_add()
    {
        if(IS_AJAX && IS_POST)//ajax提交验证
        {
        	$model = model('ProductAttribute');

            $attr_values = str_replace('_', '', I('attr_values')); // 替换特殊字符
            $attr_values = str_replace('@', '', $attr_values); // 替换特殊字符            
            $attr_values = trim($attr_values);
            
            $post_data = I('post.');
            $post_data['attr_values'] = $attr_values;

        	$savedata = array(
        		'attr_name'	=> $post_data['attr_name'],
        		'typeid'	=> $post_data['typeid'],
        		'attr_input_type'	=> isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '',
        		'attr_values'	=> isset($post_data['attr_values']) ? $post_data['attr_values'] : '',
        		'sort_order'	=> $post_data['sort_order'],
        		'add_time'	=> getTime(),
        		'update_time'	=> getTime(),
        	);

            // 数据验证            
            $validate = \think\Loader::validate('ProductAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $return_arr = array(
                    'status' => -1,
                    'msg' => $error_msg[0],
                    'data' => $error,
                );
                respose($return_arr);
            } else {
                $model->data($savedata,true); // 收集数据
                $model->save(); // 写入数据到数据库
                $insert_id = $model->getLastInsID();
                $return_arr = array(
                     'status' => 1,
                     'msg'   => '操作成功',                        
                     'data'  => array('url'=>U('Product/attribute_index', array('typeid'=>$post_data['typeid']))),
                );
                adminLog('新增产品参数：'.$savedata['attr_name']);
                respose($return_arr);
            }  
        }

        $typeid = I('param.typeid/d', 0);
        $assign_data = array();

        /*允许发布文档列表的栏目*/
        $arctype_html = allow_release_arctype($typeid, array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }

    /**
     * 编辑产品参数
     */
    public function attribute_edit()
    {
        if(IS_AJAX && IS_POST)//ajax提交验证
        {
        	$model = model('ProductAttribute');

            $attr_values = str_replace('_', '', I('attr_values')); // 替换特殊字符
            $attr_values = str_replace('@', '', $attr_values); // 替换特殊字符            
            $attr_values = trim($attr_values);
            
            $post_data = I('post.');
            $post_data['attr_values'] = $attr_values;

        	$savedata = array(
        		'attr_id'	=> $post_data['attr_id'],
        		'attr_name'	=> $post_data['attr_name'],
        		'typeid'	=> $post_data['typeid'],
        		'attr_input_type'	=> isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '',
        		'attr_values'	=> isset($post_data['attr_values']) ? $post_data['attr_values'] : '',
        		'sort_order'	=> $post_data['sort_order'],
        		'update_time'	=> getTime(),
        	);

            // 数据验证            
            $validate = \think\Loader::validate('ProductAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $return_arr = array(
                    'status' => -1,
                    'msg' => $error_msg[0],
                    'data' => $error,
                );
                respose($return_arr);
            } else {
                $model->data($savedata,true); // 收集数据
                $model->isUpdate(true)->save(); // 写入数据到数据库     
                $return_arr = array(
                     'status' => 1,
                     'msg'   => '操作成功',                        
                     'data'  => array('url'=>U('Product/attribute_index', array('typeid'=>$post_data['typeid']))),
                );
                adminLog('编辑产品参数：'.$savedata['attr_name']);
                respose($return_arr);
            }  
        }  

        $assign_data = array();

        $id = I('id/d');
        $info = M('ProductAttribute')->find($id);
        if (empty($info)) {
            $this->error('数据不存在，请联系管理员！');
            exit;
        }
        $assign_data['field'] = $info;

        /*允许发布文档列表的栏目*/
        $arctype_html = allow_release_arctype($info['typeid'], array($this->channeltype));
        $assign_data['arctype_html'] = $arctype_html;
        /*--end*/

        $this->assign($assign_data);
        return $this->fetch();
    }
    
    /**
     * 删除产品参数
     */
    public function attribute_del()
    {
        $id_arr = I('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){
            $r = M('ProductAttribute')->where("attr_id",'IN',$id_arr)->delete();
            if($r){
                adminLog('删除产品参数-id：'.implode(',', $id_arr));
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('参数有误');
        }
    }

    /**
     * 动态获取产品参数输入框 根据不同的数据返回不同的输入框类型
     */
    public function ajax_get_attr_input($typeid = '', $aid = '')
    {
        $productLogic = new ProductLogic();
        $str = $productLogic->getAttrInput($aid, $typeid);
        if (empty($str)) {
            $str = '<div style="font-size: 12px;text-align: center;">提示：该主栏目还没有参数值，若有需要请点击【<a href="'.url('Product/attribute_index', array('typeid'=>$typeid)).'">产品参数</a>】进行更多操作。</div>';
        }

        if (IS_AJAX) {
            exit($str);
        } else {
            return $str;
        }
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
        $allowReleaseChannel = array(2);

        /*允许发布文档列表的栏目*/
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
        $form_action = url('Product/move');
        $this->assign('form_action', $form_action);
        /*--end*/

        return $this->fetch('archives/move');
    }
}