<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

// 设置前台URL模式
function set_home_url_mode() {
    $uiset = I('param.uiset/s', 'off');
    $uiset = trim($uiset, '/');
    $seo_pseudo = tpCache('seo.seo_pseudo');
    if ($seo_pseudo == 1 || $uiset == 'on') {
        config('url_common_param', true);
        config('url_route_on', false);
    } elseif ($seo_pseudo == 2 && $uiset != 'on') {
        config('url_common_param', false);
        config('url_route_on', true);
    } elseif ($seo_pseudo == 3 && $uiset != 'on') {
        config('url_common_param', false);
        config('url_route_on', true);
    }
}

/**
 * 设置内容标题
 */
function set_arcseotitle($title = '', $seo_title = '', $typename = '')
{
    /*针对没有自定义SEO标题的文档*/
    if (empty($seo_title)) {
        $web_name = tpCache('web.web_name');
        $seo_viewtitle_format = tpCache('seo.seo_viewtitle_format');
        switch ($seo_viewtitle_format) {
            case '1':
                $seo_title = $title;
                break;
            
            case '3':
                $seo_title = $title.'_'.$typename.'_'.$web_name;
                break;
            
            case '2':
            default:
                $seo_title = $title.'_'.$web_name;
                break;
        }
    }
    /*--end*/

    return $seo_title;
}

/**
 * 设置栏目标题
 */
function set_typeseotitle($typename = '', $seo_title = '')
{
    /*针对没有自定义SEO标题的列表*/
    if (empty($seo_title)) {
        $web_name = tpCache('web.web_name');
        $seo_liststitle_format = tpCache('seo.seo_liststitle_format');
        switch ($seo_liststitle_format) {
            case '1':
                $seo_title = $typename.'_'.$web_name;
                break;
            
            case '2':
            default:
                $page = I('param.page/d', 1);
                if ($page > 1) {
                    $typename .= "_第{$page}页";
                }
                $seo_title = $typename.'_'.$web_name;
                break;
        }
    }

    return $seo_title;
}

/**
 * 获取当前栏目的第一级栏目
 */
function gettoptype($typeid, $field = 'typename')
{
    $parent_list = model('Arctype')->getAllPid($typeid); // 获取当前栏目的所有父级栏目
    $result = current($parent_list); // 第一级栏目
    if (isset($result[$field]) && !empty($result[$field])) {
        return $result[$field];
    } else {
        return '';
    }
}