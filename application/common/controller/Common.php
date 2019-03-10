<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\common\controller;
use think\Controller;
use think\Session;
class Common extends Controller {

    public $session_id;
    public $theme_style = '';
    public $view_suffix = 'html';
    public $eyou = array();

    /**
     * 析构函数
     */
    function __construct() 
    {
        /*是否隐藏或显示应用入口index.php*/
        if (tpCache('seo.seo_inlet') == 0) {
            \think\Url::root('/index.php');
        } else {
            // \think\Url::root('/');
        }
        /*--end*/
        parent::__construct();
    }    
    
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        if (!session_id()) {
            Session::start();
        }
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 
        $this->session_id = session_id(); // 当前的 session_id
        !defined('SESSION_ID') && define('SESSION_ID', $this->session_id); //将当前的session_id保存为常量，供其它方法调用

        /*关闭网站*/
        if (tpCache('web.web_status') == 1) {
            die("<div style='text-align:center; font-size:20px; font-weight:bold; margin:50px 0px;'>网站暂时关闭，维护中……</div>");
        }
        /*--end*/

        $this->global_assign(); // 获取网站全局变量值
        $this->view_suffix = config('template.view_suffix'); // 模板后缀htm
        $this->theme_style = THEME_STYLE; // 模板目录
        $this->eyou['global'] = tpCache('global'); //全局变量

        /*电脑版与手机版的切换*/
        $v = I('param.v/s', 'pc');
        $v = trim($v, '/');
        $this->assign('v', $v);
        /*--end*/
    }

    /**
     * 获取系统内置变量 
     */
    public function global_assign()
    {
        $globalParams = tpCache('global');
        $this->assign('global', $globalParams);
    }
}