<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;
use think\Db;
use think\Cache;

class System extends Base
{
    public function index()
    {
        $this->redirect(url('System/web'));
    }

    /**
     * 网站设置
     */
    public function web()
    {
        $inc_type =  'web';

        if (IS_POST) {
            $param = I('post.');
            $param['web_keywords'] = str_replace('，', ',', $param['web_keywords']);
            $param['web_description'] = filter_line_return($param['web_description']);
            
            // 网站根网址
            $web_basehost = rtrim($param['web_basehost'], '/');
            if (!is_http_url($web_basehost) && !empty($web_basehost)) {
                $web_basehost = 'http://'.$web_basehost;
            }
            $param['web_basehost'] = $web_basehost;

            // 网站logo
            $web_logo_is_remote = !empty($param['web_logo_is_remote']) ? $param['web_logo_is_remote'] : 0;
            $web_logo = '';
            if ($web_logo_is_remote == 1) {
                $web_logo = $param['web_logo_remote'];
            } else {
                $web_logo = $param['web_logo_local'];
            }
            $param['web_logo'] = $web_logo;
            unset($param['web_logo_is_remote']);
            unset($param['web_logo_remote']);
            unset($param['web_logo_local']);

            // 浏览器地址图标
            if (!empty($param['web_ico']) && !is_http_url($param['web_ico'])) {
                $web_ico = trim($param['web_ico'], '/');
                $source = ROOT_PATH.$web_ico;
                $destination = ROOT_PATH.'favicon.ico';
                if (file_exists($source) && copy($source, $destination)) {
                    $param['web_ico'] = '/favicon.ico';
                }
            }

            tpCache($inc_type, $param);
            write_global_params(); // 写入全局内置参数
            $this->success('操作成功', U('System/web'));
            exit;
        }

        $config = tpCache($inc_type);
        // 网站logo
        if (is_http_url($config['web_logo'])) {
            $config['web_logo_is_remote'] = 1;
            $config['web_logo_remote'] = $config['web_logo'];
        } else {
            $config['web_logo_is_remote'] = 0;
            $config['web_logo_local'] = $config['web_logo'];
        }
        
        /*系统模式*/
        $web_cmsmode = isset($config['web_cmsmode']) ? $config['web_cmsmode'] : 2;
        $this->assign('web_cmsmode', $web_cmsmode);
        /*--end*/

        /*自定义变量*/
        $eyou_row = M('config_attribute')->field('a.attr_id, a.attr_name, a.attr_var_name, a.attr_input_type, b.value, b.id, b.name')
            ->alias('a')
            ->join('__CONFIG__ b', 'b.name = a.attr_var_name', 'LEFT')
            ->where('a.inc_type', $inc_type)
            ->where('b.is_del', 0)
            ->order('a.attr_id asc')
            ->select();
        $this->assign('eyou_row',$eyou_row);
        /*--end*/

        $this->assign('config',$config);//当前配置项
        return $this->fetch();
    }

    /**
     * 核心设置
     */
    public function web2()
    {
        $inc_type = 'web';

        if (IS_POST) {
            $param = I('post.');

            /*EyouCMS安装目录*/
            $web_cmspath = trim($param['web_cmspath'], '/');
            $web_cmspath = !empty($web_cmspath) ? '/'.$web_cmspath : '';
            $param['web_cmspath'] = $web_cmspath;
            /*--end*/
            /*插件入口*/
            $web_weapp_switch = $param['web_weapp_switch'];
            $web_weapp_switch_old = tpCache('web.web_weapp_switch');
            /*--end*/
            /*自定义后台路径名*/
            $adminbasefile = trim($param['adminbasefile']).'.php'; // 新的文件名
            $param['web_adminbasefile'] = '/'.$adminbasefile;
            $adminbasefile_old = trim($param['adminbasefile_old']).'.php'; // 旧的文件名
            unset($param['adminbasefile']);
            unset($param['adminbasefile_old']);
            if ('index.php' == $adminbasefile) {
                $this->error("新后台地址禁止使用index", null, '', 1);
            }
            /*--end*/
            $param['web_sqldatapath'] = '/'.trim($param['web_sqldatapath'], '/'); // 数据库备份目录
            $param['web_htmlcache_expires_in'] = intval($param['web_htmlcache_expires_in']); // 页面缓存有效期

            tpCache($inc_type,$param);
            write_global_params(); // 写入全局内置参数

            $refresh = false;
            $gourl = SITE_URL.'/'.$adminbasefile;
            /*更改自定义后台路径名*/
            if ($adminbasefile_old != $adminbasefile && eyPreventShell($adminbasefile_old)) {
                if (file_exists($adminbasefile_old)) {
                    if(rename($adminbasefile_old, $adminbasefile)) {
                        $refresh = true;
                    }
                } else {
                    $this->error("根目录{$adminbasefile_old}文件不存在！", null, '', 2);
                }
            }
            /*--end*/

            /*更改插件入口*/
            if ($web_weapp_switch_old != $web_weapp_switch) {
                $refresh = true;
            }
            /*--end*/
            
            /*刷新整个后台*/
            if ($refresh) {
                $this->success('操作成功', $gourl, '', 1, [], '_parent');
            }
            /*--end*/

            $this->success('操作成功', U('System/web2'));
        }

        $config = tpCache($inc_type);
        //自定义后台路径名
        $web_adminbasefile = !empty($config['web_adminbasefile']) ? $config['web_adminbasefile'] : request()->baseFile();
        $adminbasefile = preg_replace('/^\/(.*)\.([^\.]+)$/i', '$1', $web_adminbasefile);
        $this->assign('adminbasefile', $adminbasefile);
        // 数据库备份目录
        $sqlbackuppath = config('DATA_BACKUP_PATH');
        $this->assign('sqlbackuppath', $sqlbackuppath);

        $this->assign('config',$config);//当前配置项
        return $this->fetch();
    }

    /**
     * 附件设置
     */
    public function basic()
    {
        $inc_type =  'basic';

        if (IS_POST) {
            $param = I('post.');
            tpCache($inc_type,$param);
            write_global_params(); // 写入全局内置参数
            $this->success('操作成功', U('System/basic'));
        }

        $config = tpCache($inc_type);
        $this->assign('config',$config);//当前配置项
        return $this->fetch();
    }

    /**
     * 图片水印
     */
    public function water()
    {
        $inc_type =  'water';

        if (IS_POST) {
            $param = I('post.');

            $mark_img_is_remote = !empty($param['mark_img_is_remote']) ? $param['mark_img_is_remote'] : 0;
            $mark_img = '';
            if ($mark_img_is_remote == 1) {
                $mark_img = $param['mark_img_remote'];
            } else {
                $mark_img = $param['mark_img_local'];
            }
            $param['mark_img'] = $mark_img;
            unset($param['mark_img_is_remote']);
            unset($param['mark_img_remote']);
            unset($param['mark_img_local']);

            tpCache($inc_type,$param);
            write_global_params(); // 写入全局内置参数
            $this->success('操作成功', U('System/water'));
        }

        $config = tpCache($inc_type);
        if (is_http_url($config['mark_img'])) {
            $config['mark_img_is_remote'] = 1;
            $config['mark_img_remote'] = $config['mark_img'];
        } else {
            $config['mark_img_is_remote'] = 0;
            $config['mark_img_local'] = $config['mark_img'];
        }

        $this->assign('config',$config);//当前配置项
        return $this->fetch();
    }

    /**
     * 清空缓存
     */
    public function clearCache($arr = array())
    {
        if (IS_POST) {
            $post = I('post.');

            if (!empty($post['clearHtml'])) { // 清除页面缓存
                $this->clearHtmlCache($post['clearHtml']);
            }

            if (!empty($post['clearCache'])) { // 清除数据缓存
                $this->clearSystemCache($post['clearCache']);
            }

            /*兼容每个用户的自定义字段，重新生成数据表字段缓存文件*/
            try {
                schemaTable('arctype');
            } catch (Exception $e) {}
            try {
                schemaTable('article_content');
            } catch (Exception $e) {}
            try {
                schemaTable('download_content');
            } catch (Exception $e) {}
            try {
                schemaTable('images_content');
            } catch (Exception $e) {}
            try {
                schemaTable('product_content');
            } catch (Exception $e) {}
            try {
                schemaTable('single_content');
            } catch (Exception $e) {}
            /*--end*/

            /*清除旧升级备份包，保留最后一个*/
            $backupArr = glob(DATA_PATH.'backup/v*_www');
            for ($i=0; $i < count($backupArr) - 1; $i++) { 
                delFile($backupArr[$i], true);
            }
            /*--end*/

            $this->success('操作成功！', U('Index/welcome'));
        }
        
        return $this->fetch();
    }

    /**
     * 清空数据缓存
     */
    public function fastClearCache($arr = array())
    {
        $this->clearSystemCache();
        $script = "<script>parent.layer.msg('操作成功', {time:3000,icon: 1});window.location='".U('Index/welcome')."';</script>";
        echo $script;
    }

    /**
     * 清空数据缓存
     */
    public function clearSystemCache($arr = array())
    {
        if (empty($arr)) {
            delFile(rtrim(RUNTIME_PATH, '/'));
            Cache::clear();
        } else {
            foreach ($arr as $key => $val) {
                delFile(RUNTIME_PATH.$val);
            }
        }
        tpCache('global');

        return true;
    }

    /**
     * 清空页面缓存
     */
    public function clearHtmlCache($arr = array())
    {
        if (empty($arr)) {
            delFile(rtrim(HTML_ROOT, '/'));
        } else {
            foreach ($arr as $key => $val) {
                foreach (['http','https'] as $sk1 => $sv1) {
                    foreach (['pc','mobile'] as $sk2 => $sv2) {
                        delFile(HTML_ROOT."{$sv1}/{$sv2}/{$val}");
                        delFile(HTML_ROOT."{$sv1}/{$sv2}/cache/{$val}");
                    }
                }
                if ($val == 'index') {
                    foreach (['index.html','indexs.html'] as $sk1 => $sv1) {
                        $filename = ROOT_PATH.$sv1;
                        if (file_exists($filename)) {
                            @unlink($filename);
                        }
                    }
                }
            }
        }
    }

    /**
     * 地区设置
     */
    public function region()
    {
        $parent_id = I('parent_id',0);
        if($parent_id == 0){
            $parent = array('id'=>0,'name'=>"中国省份地区",'level'=>0, 'parent_id'=>0);
        }else{
            $parent = M('region')->where("id" ,$parent_id)->find();
        }
        $names = $this->getParentRegionList($parent_id);
        if(!empty($names)){
            $names = array_reverse($names);
            $parent_path = implode($names, '>');
        }
        $region = M('region')->where("parent_id" , $parent_id)->select();
        $this->assign('parent',$parent);
        $this->assign('parent_path',$parent_path);
        $this->assign('region',$region);
        return $this->fetch();
    }

    /**
     * 寻找Region_id的父级字段, $column可自己指定
     * @param $parent_id
     * @return array
     */
    function getParentRegionList($parent_id)
    {
        $names = array();
        $region =  M('region')->where(array('id'=>$parent_id))->find();
        array_push($names,$region['name']);
        if($region['parent_id'] != 0){
            $nregion = $this->getParentRegionList($region['parent_id']);
            if(!empty($nregion)){
                $names = array_merge($names, $nregion);
            }
        }
        return $names;
    }
    
    /**
     * 添加地区
     */
    public function region_add()
    {
        $data = I('post.');
        $referurl =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("System/region");
        $data['level'] = $data['level']+1;
        if(empty($data['name'])){
            $this->error("请填写地区名称", $referurl);
        }else{
            $res = M('region')->where("parent_id = ".$data['parent_id']." and name='".$data['name']."'")->find();
            if(empty($res)){
                // 清除一下缓存
                \think\Cache::clear('region');
                M('region')->add($data);
                $this->success("操作成功", $referurl);
            }else{
                $this->error("该区域下已有该地区，请检查", $referurl);
            }
        }
    }
    
    /**
     * 删除地区
     */
    public function region_del()
    {
        $id = I('id/d', 0);
        $referurl =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("System/region");
        if ($id) {
            M('region')->where("id=$id or parent_id=$id")->delete();
            // 清除一下缓存
            \think\Cache::clear('region');
            $this->success("操作成功", $referurl);
        } else {
            $this->error("操作失败", $referurl);
        }
    }
      
    /**
     * 发送测试邮件
     */
    public function send_email()
    {
        $param = I('post.');
        $res = send_email($param['smtp_test_eamil'],'拾叁网络科技','拾叁网络科技验证码:'.mt_rand(1000,9999), 1);
        exit(json_encode($res));
    }

    /**
     * 新增自定义变量
     */
    public function customvar()
    {
        if (IS_POST) {
            $configAttributeM = model('ConfigAttribute');

            $post_data = I('post.');
            $attr_input_type = isset($post_data['attr_input_type']) ? $post_data['attr_input_type'] : '';

            if ($attr_input_type == 3) {
                // 本地/远程图片上传的处理
                $is_remote = !empty($post_data['is_remote']) ? $post_data['is_remote'] : 0;
                $litpic = '';
                if ($is_remote == 1) {
                    $litpic = $post_data['value_remote'];
                } else {
                    $litpic = $post_data['value_local'];
                }
                $attr_values = $litpic;
            } else {
                $attr_values = I('attr_values');
                // $attr_values = str_replace('_', '', $attr_values); // 替换特殊字符
                // $attr_values = str_replace('@', '', $attr_values); // 替换特殊字符
                $attr_values = trim($attr_values);
                $attr_values = isset($attr_values) ? $attr_values : '';
            }

            $savedata = array(
                'inc_type'    => $post_data['inc_type'],
                'attr_name' => $post_data['attr_name'],
                'attr_input_type'   => $attr_input_type,
                'attr_values'   => $attr_values,
                'update_time'   => getTime(),
            );

            // 数据验证            
            $validate = \think\Loader::validate('ConfigAttribute');
            if(!$validate->batch()->check($savedata))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $return_arr = array(
                    'errcode'   => -1,
                    'errmsg'    => $error_msg[0],
                );
                respose($return_arr);
            } else {
                if (isset($post_data['id']) && $post_data['id'] > 0) {
                    $savedata['attr_id'] = $post_data['attr_id'];
                    $configAttributeM->data($savedata,true); // 收集数据
                    $configAttributeM->isUpdate(true)->save(); // 写入数据到数据库  
                    // 更新变量名
                    $attr_var_name = $post_data['name'];
                    adminLog('编辑自定义变量：'.$savedata['attr_name']);
                } else {
                    $savedata['add_time'] = getTime();
                    $configAttributeM->data($savedata,true); // 收集数据
                    $configAttributeM->save(); // 写入数据到数据库
                    $insert_id = $configAttributeM->getLastInsID();
                    // 更新变量名
                    $attr_var_name = $post_data['inc_type'].'_attr_'.$insert_id;
                    $map = array(
                        'attr_id'   => $insert_id,
                    );
                    M('ConfigAttribute')->where($map)->update(array('attr_var_name'=>$attr_var_name));
                    adminLog('新增自定义变量：'.$savedata['attr_name']);
                }

                // 保存到config表，更新缓存
                $inc_type = $post_data['inc_type'];
                $configData = array(
                    $attr_var_name  => $attr_values,
                );
                tpCache($inc_type, $configData);

                $return_arr = array(
                    'errcode'   => 0,
                    'errmsg'    => '操作成功',
                );
                respose($return_arr);
            }  
        }

        $id = I('param.id/d', 0);
        $field = array();
        if ($id > 0) {
            $field = M('config')->field('a.id, a.value, a.name, b.attr_id, b.attr_name, b.attr_input_type')
                ->alias('a')
                ->join('__CONFIG_ATTRIBUTE__ b', 'a.name = b.attr_var_name', 'LEFT')
                ->find($id);
            if ($field['attr_input_type'] == 3) {
                if (is_http_url($field['value'])) {
                    $field['is_remote'] = 1;
                    $field['value_remote'] = $field['value'];
                } else {
                    $field['is_remote'] = 0;
                    $field['value_local'] = $field['value'];
                }
            }
        }
        $this->assign('field', $field);

        $inc_type = I('param.inc_type/s', '');
        $this->assign('inc_type', $inc_type);

        return $this->fetch();
    }

    /**
     * 删除自定义变量
     */
    public function customvar_del()
    {
        $id = I('del_id/d');
        if(!empty($id)){
            $attr_var_name = M('config')->where("id = $id")->getField('name');

            $r = M('config')->where("id = $id")->update(array('is_del'=>1, 'update_time'=>getTime()));
            if($r){
                M('config_attribute')->where(array('attr_var_name'=>array('eq', $attr_var_name)))->update(array('update_time'=>getTime()));
                adminLog('删除自定义变量：'.$attr_var_name);
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('参数有误');
        }
    }

    /**
     * 恢复自定义变量
     */
    public function customvar_recovery()
    {
        $id = I('del_id/d');
        if(!empty($id)){
            $attr_var_name = M('config')->where("id = $id")->getField('name');

            $r = M('config')->where("id = $id")->update(array('is_del'=>0, 'update_time'=>getTime()));
            if($r){
                adminLog('恢复自定义变量：'.$attr_var_name);
                respose(array('status'=>1, 'msg'=>'删除成功'));
            }else{
                respose(array('status'=>0, 'msg'=>'删除失败'));
            }
        }else{
            respose(array('status'=>0, 'msg'=>'参数有误'));
        }
    }

    /**
     * 自定义变量回收站列表
     */
    public function customvar_recycle()
    {
        $list = array();
        $condition = array();
        // 应用搜索条件
        $attr_var_names = M('config')->field('name')->where('is_del',1)->getAllWithIndex('name');
        $condition['a.attr_var_name'] = array('IN', array_keys($attr_var_names));

        $count = M('config_attribute')->alias('a')->where($condition)->count();// 查询满足要求的总记录数
        $Page = new \think\Page($count, config('paginate.list_rows'));// 实例化分页类 传入总记录数和每页显示的记录数
        $list = M('config_attribute')->alias('a')
            ->field('a.*, b.id')
            ->join('__CONFIG__ b', 'b.name = a.attr_var_name', 'LEFT')
            ->where($condition)
            ->order('a.update_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('list',$list);// 赋值数据集
        $this->assign('pager',$Page);// 赋值分页对象

        return $this->fetch();
    }

    /**
     * 彻底删除自定义变量
     */
    public function customvar_del_thorough()
    {
        $id = I('del_id/d');
        if(!empty($id)){
            $attr_var_name = M('config')->where("id = $id")->getField('name');

            $r = M('config')->where("id = $id")->delete();
            if($r){
                // 同时删除
                M('config_attribute')->where(array('attr_var_name'=>array('eq', $attr_var_name)))->delete();
                adminLog('彻底删除自定义变量：'.$attr_var_name);
                respose(array('status'=>1, 'msg'=>'删除成功'));
            }else{
                respose(array('status'=>0, 'msg'=>'删除失败'));
            }
        }else{
            respose(array('status'=>0, 'msg'=>'参数有误'));
        }
    }
}