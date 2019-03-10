<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

$home_rewrite = array();
$route = array(
    '__pattern__' => array(
        'tid' => '\w+',
        'aid' => '\d+',
    ),
    '__alias__' => array(),
    '__domain__' => array(),
);

$globalConfig = tpCache('global');
// mysql的sql-mode模式参数
$system_sql_mode = !empty($globalConfig['system_sql_mode']) ? $globalConfig['system_sql_mode'] : config('ey_config.system_sql_mode');
config('ey_config.system_sql_mode', $system_sql_mode);
// URL模式
$seo_pseudo = !empty($globalConfig['seo_pseudo']) ? intval($globalConfig['seo_pseudo']) : config('ey_config.seo_pseudo');

$uiset = I('param.uiset/s', 'off');
if ('on' == trim($uiset, '/')) { // 可视化页面必须是兼容模式的URL
    config('ey_config.seo_inlet', 0);
    config('ey_config.seo_pseudo', 1);
    config('ey_config.seo_dynamic_format', 1);
} else {
    // URL模式
    config('ey_config.seo_pseudo', $seo_pseudo);
    // 动态URL格式
    $seo_dynamic_format = !empty($globalConfig['seo_dynamic_format']) ? intval($globalConfig['seo_dynamic_format']) : config('ey_config.seo_dynamic_format');
    config('ey_config.seo_dynamic_format', $seo_dynamic_format);
    // 伪静态格式
    $seo_rewrite_format = !empty($globalConfig['seo_rewrite_format']) ? intval($globalConfig['seo_rewrite_format']) : config('ey_config.seo_rewrite_format');
    config('ey_config.seo_rewrite_format', $seo_rewrite_format); 
    // 是否隐藏入口文件
    $seo_inlet = !empty($globalConfig['seo_inlet']) ? $globalConfig['seo_inlet'] : config('ey_config.seo_inlet');
    config('ey_config.seo_inlet', $seo_inlet);

    if (3 == $seo_pseudo) {
        if (1 == $seo_rewrite_format) { // 精简伪静态
            $home_rewrite = array(
                // 列表页
                '<tid>$' => array('home/Lists/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                // 内容页
                '<dirname>/<aid>$' => array('home/View/index',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 留言提交
                'guestbook/submit$' => array('home/Lists/gbook_submit',array('method' => 'post', 'ext' => 'html'), 'cache'=>1),
                // 下载文件
                'downfile/<id>/<uhash>$' => array('home/View/downfile',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 标签伪静态
                'tags$' => array('home/Tags/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                'tags/<tagid>$' => array('home/Tags/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 搜索伪静态
                'search$' => array('home/Search/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
            );
        } else {
            $home_rewrite = array(
                // 文章模型伪静态
                'article$' => array('home/Article/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'article/<tid>$' => array('home/Article/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'article/<dirname>/<aid>$' => array('home/Article/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 产品模型伪静态
                'product$' => array('home/Product/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'product/<tid>$' => array('home/Product/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'product/<dirname>/<aid>$' => array('home/Product/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 图集模型伪静态
                'images$' => array('home/Images/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'images/<tid>$' => array('home/Images/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'images/<dirname>/<aid>$' => array('home/Images/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 下载模型伪静态
                'download$' => array('home/Download/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'download/<tid>$' => array('home/Download/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'download/<dirname>/<aid>$' => array('home/Download/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                'downfile/<id>/<uhash>$' => array('home/View/downfile',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 单页模型伪静态
                'single$' => array('home/Single/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'single/<tid>$' => array('home/Single/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'single/<dirname>/<aid>$' => array('home/Single/view',array('method' => 'get', 'ext' => 'html'),'cache'=>1),
                // 标签伪静态
                'tags$' => array('home/Tags/index',array('method' => 'get', 'ext' => ''), 'cache'=>1),
                'tags/<tagid>$' => array('home/Tags/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 搜索伪静态
                'search$' => array('home/Search/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                // 留言模型
                'guestbook$' => array('home/Guestbook/index',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'guestbook/<tid>$' => array('home/Guestbook/lists',array('method' => 'get', 'ext' => 'html'), 'cache'=>1),
                'guestbook/submit$' => array('home/View/submit',array('method' => 'post', 'ext' => 'html'), 'cache'=>1),
            );
        }
    }

    /*插件模块路由*/
    $weapp_route_file = 'weapp/route.php';
    if (file_exists(APP_PATH.$weapp_route_file)) {
        $weapp_route = include_once $weapp_route_file;
        $route = array_merge($weapp_route, $route);
    }
    /*--end*/
}

$route = array_merge($route, $home_rewrite);

return $route;
