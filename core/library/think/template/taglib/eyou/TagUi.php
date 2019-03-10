<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 外观调试的最初引入文件
 */
class TagUi extends Base
{
    public $uiset = 'off';

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->uiset = I("param.uiset/s", "off");
        $this->uiset = trim($this->uiset, '/');
    }

    /**
     * 纯文本编辑
     * @author wengxianhu by 2018-4-20
     */
    public function getUi()
    {
        $v = I('param.v/s', 'pc');
        $v = trim($v, '/');
        $parseStr = '';
        if ("on" == $this->uiset) {

            /*权限控制 by 小虎哥*/
            $config = config('session');
            $admin_info = $_SESSION[$config['prefix']]['admin_info'];
            if (0 < intval($admin_info['role_id'])) {
                if(empty($admin_info['auth_role_info'])){
                    return '';
                }
                $auth_role_info = $admin_info['auth_role_info'];
                $permission = $auth_role_info['permission'];
                $auth_rule = include APP_PATH.'admin/conf/auth_rule.php';
                $all_auths = []; // 系统全部权限对应的菜单ID
                $admin_auths = []; // 用户当前拥有权限对应的菜单ID
                $diff_auths = []; // 用户没有被授权的权限对应的菜单ID
                foreach($auth_rule as $key => $val){
                    $all_auths = array_merge($all_auths, explode(',', $val['menu_id']), explode(',', $val['menu_id2']));
                    if (in_array($val['id'], $permission['rules'])) {
                        $admin_auths = array_merge($admin_auths, explode(',', $val['menu_id']), explode(',', $val['menu_id2']));
                    }
                }
                $all_auths = array_unique($all_auths);
                $admin_auths = array_unique($admin_auths);
                $diff_auths = array_diff($all_auths, $admin_auths);

                if(in_array(2002, $diff_auths)){
                    return '';
                }
            }
            /*--end*/
            
            $version = getCmsVersion();
            $webConfig = tpCache('web');
            $web_cmspath = !empty($webConfig['web_cmspath']) ? $webConfig['web_cmspath'] : ''; // CMS安装路径
            $web_adminbasefile = !empty($webConfig['web_adminbasefile']) ? $webConfig['web_adminbasefile'] : '/login.php'; // 后台入口文件路径
            // $parseStr .= static_version($web_cmspath.'/public/plugins/layer-v3.1.0/layer.js');
            $parseStr .= '<script type="text/javascript" src="'.$web_cmspath.'/public/plugins/layer-v3.1.0/layer.js"></script>';
            $parseStr .= '<link rel="stylesheet" type="text/css" href="'.$web_cmspath.'/public/static/common/css/eyou.css?v='.$version.'" />';
            $parseStr .= '<script type="text/javascript">var admin_basefile = "'.$web_adminbasefile.'"; var admin_module_name = "admin"; var v = "'.$v.'";</script>';
            $parseStr .= '<script type="text/javascript" src="'.$web_cmspath.'/public/static/common/js/eyou.js?v='.$version.'"></script>';
        }

        return $parseStr;
    }
}