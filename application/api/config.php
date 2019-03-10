<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

// 加载系统语言包
/*\think\Lang::load([
    APP_PATH . 'admin' . DS . 'lang' . DS . request()->langset() . EXT,
]);*/

$api_config = array(
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'public:dispatch_jump',
    // 默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'public:dispatch_jump',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件 
    //'exception_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',
    // errorpage 错误页面
    //'error_tmpl'         => ROOT_PATH.'public/static/errpage/404.html',

    // 过滤不需要登录的操作
    'filter_login_action' => array(
        'Admin@login', // 登录
        'Admin@logout', // 退出
        'Admin@vertify', // 验证码
    ),
    
    // 无需验证权限的操作
    'uneed_check_action' => array(
        'Base@*', // 基类
        'Index@*', // 后台首页
        'Ajax@*', // 所有ajax操作
        'Ueditor@*', // 编辑器上传
        'Uploadify@*', // 图片上传
    ),
);

$html_config = include_once 'html.php';
return array_merge($api_config, $html_config);
?>