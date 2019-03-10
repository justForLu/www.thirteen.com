<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */
// 整站cache缓存key键值存放处，统一管理
// 注意：键名要唯一，不然会出现缓存错乱。
// 参考格式如下：
// 格式：模块_控制器_操作名[_序号]
// 1、中括号的序号可选，在同一个操作名内用于区分开。
// 2、键名不区分大写小写，要注意大小写，系统自己转为小写处理在md5()加密处理。

return array(
    /* -------------------------全局使用------------------------- */
    'global_get_arcrank_list'     => array(
        'tag'=>'arcrank', 'options'=>array('expire'=>86400, 'prefix'=>'')
    ),
    'common_getEveryTopDirnameList_model'     => array(
        'tag'=>'arctype', 'options'=>array('expire'=>0, 'prefix'=>'')
    ),

    /* -------------------------前台使用------------------------- */
    // 'home_base_global_assign'     => array(
    //     'tag'=>'home_base', 'options'=>array('expire'=>43200, 'prefix'=>'')
    // ),

    /* -------------------------后台使用------------------------- */
    'admin_all_menu'     => array(
        'tag'=>'admin_common', 'options'=>array('expire'=>43200, 'prefix'=>'')
    ),
    'admin_auth_role_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>-1, 'prefix'=>'')
    ),
    'admin_auth_modular_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>-1, 'prefix'=>'')
    ),
    'admin_channeltype_list_logic'     => array(
        'tag'=>'admin_logic', 'options'=>array('expire'=>86400, 'prefix'=>'')
    ),
);