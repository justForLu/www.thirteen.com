<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

$plugins_route = array();

/*引入全部插件的路由配置*/
$route_list = glob(WEAPP_DIR_NAME.DS.'*'.DS.'route.php');
if (!empty($route_list)) {
    foreach ($route_list as $key => $file) {
        $route_value = include_once $file;
        if (!empty($route_value)) {
            $plugins_route = array_merge($route_value, $plugins_route);
        }
    }
}
/*--end*/

$route = array(
    '__pattern__' => array(),
    '__alias__' => array(),
    '__domain__' => array(),
);

$route = array_merge($route, $plugins_route);

return $route;
