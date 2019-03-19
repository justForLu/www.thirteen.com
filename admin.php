<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月5日
 *  管理后台入口文件
 */

// 定义为入口文件
define('IS_INDEX', true);

// 入口文件地址绑定
define('URL_BLIND', 'admin');

// PHP版本检测
if (PHP_VERSION < '5.3') {
    header('Content-Type:text/html; charset=utf-8');
    exit('您服务器PHP的版本太低，程序要求PHP版本不小于5.3');
}

// 引用内核启动文件
require dirname(__FILE__) . '/core/start.php';
