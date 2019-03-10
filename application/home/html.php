<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */


$html_cache_arr = array();
// 全局变量数组
$global = tpCache('global');
// 系统模式
$web_cmsmode = isset($global['web_cmsmode']) ? $global['web_cmsmode'] : 2;
/*页面缓存有效期*/
$app_debug = true;
$web_htmlcache_expires_in = -1;
if (1 == $web_cmsmode) { // 运营模式
    $app_debug = false;
    $web_htmlcache_expires_in = isset($global['web_htmlcache_expires_in']) ? $global['web_htmlcache_expires_in'] : 7200;
    $html_cache_arr = array(
        // 首页
        array('mca'=>'home_Index_index', 'filename'=>'index/index', 'cache'=>$web_htmlcache_expires_in),

        // [普通伪静态]文章
        array('mca'=>'home_Article_index', 'filename'=>'channel/article/index', 'cache'=>$web_htmlcache_expires_in),  
        array('mca'=>'home_Article_lists', 'filename'=>'articlelist/article/list', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Article_view', 'filename'=>'detail/article/view', 'p'=>array('aid'), 'cache'=>$web_htmlcache_expires_in), 
        // [普通伪静态]产品
        array('mca'=>'home_Product_index', 'filename'=>'channel/product/index', 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Product_lists', 'filename'=>'articlelist/product/list', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Product_view', 'filename'=>'detail/product/view', 'p'=>array('aid'), 'cache'=>$web_htmlcache_expires_in),
        // [普通伪静态]图集
        array('mca'=>'home_Images_index', 'filename'=>'channel/images/index', 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Images_lists', 'filename'=>'articlelist/images/list', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Images_view', 'filename'=>'detail/images/view', 'p'=>array('aid'), 'cache'=>$web_htmlcache_expires_in),
        // [普通伪静态]下载
        array('mca'=>'home_Download_index', 'filename'=>'channel/download/index', 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Download_lists', 'filename'=>'articlelist/download/list', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Download_view', 'filename'=>'detail/download/view', 'p'=>array('aid'), 'cache'=>$web_htmlcache_expires_in),
        // [普通伪静态]单页
        array('mca'=>'home_Single_index', 'filename'=>'channel/single/index', 'cache'=>$web_htmlcache_expires_in), 
        array('mca'=>'home_Single_lists', 'filename'=>'articlelist/single/list', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        // [超短伪静态]列表页
        array('mca'=>'home_Lists_index', 'filename'=>'articlelist/lists/index', 'p'=>array('tid','page'), 'cache'=>$web_htmlcache_expires_in), 
        // [超短伪静态]内容页
        array('mca'=>'home_View_index', 'filename'=>'detail/view/index', 'p'=>array('aid'), 'cache'=>$web_htmlcache_expires_in), 
    );
}
/*--end*/

return array(
    // 应用调试模式
    'app_debug' => $app_debug,
    // 模板设置
    'template' => array(
        // 模板路径
        'view_path' => './template/',
        // 模板后缀
        'view_suffix' => 'htm',
    ),
    // 视图输出字符串内容替换
    'view_replace_str' => array(),

    /**假设这个访问地址是 www.xxxxx.dev/home/goods/goodsInfo/id/1.html 
     *就保存名字为 index_goods_goodsinfo_1.html     
     *配置成这样, 指定 模块 控制器 方法名 参数名
     */
    'HTML_CACHE_ARR'=> $html_cache_arr,
);
?>