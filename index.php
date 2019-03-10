<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */
header("Content-type:text/html;charset=utf-8");
// [ 应用入口文件 ]
if (extension_loaded('zlib')){
	try{
	    ob_end_clean();
	} catch(Exception $e) {

	}
    ob_start('ob_gzhandler');
}
// error_reporting(E_ALL ^ E_NOTICE);//显示除去 E_NOTICE 之外的所有错误信息
error_reporting(E_ERROR | E_WARNING | E_PARSE);//报告运行时错误

// 当前是http还是https协议
$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
// 网站域名
define('DOMAIN', $_SERVER['HTTP_HOST']); 
// 带http/https网站域名
define('SITE_URL', $http.'://'.DOMAIN); 
// 不带前缀的域名-网站根域名
$pattern = '/^([^.]+)\.([^\/]+)$/';
$url_suffix = preg_replace($pattern, '$2', DOMAIN);
define('ROOT_SITE_URL', '.'.$url_suffix);
// 编辑器图片上传相对路径
define('UPLOAD_PATH','uploads/'); 
// 缓存时间
define('EYOUCMS_CACHE_TIME', 86400);
// https主域名
define('HTTPS_SITE_URL', 'https://'.DOMAIN);
// 数据绝对路径
define('DATA_PATH', __DIR__ . '/data/');
// 运行缓存
define('RUNTIME_PATH', DATA_PATH . 'runtime/');
// 静态页面文件目录，存储静态页面文件
define('HTML_ROOT', RUNTIME_PATH . 'html/'); 
// 静态页面文件目录，存储静态页面文件
define('HTML_PATH', HTML_ROOT . $http.'/'); 
// 静态页面缓存文件目录，存储不指定文件名的静态页面文件
define('HTML_OTHER_PATH', HTML_PATH . 'other/'); 
// 静态页面缓存文件目录， 存储cache方式的页面缓存
define('HTML_CACHE_PATH', HTML_PATH . 'cache/'); 
// 安装程序定义
define('DEFAULT_INSTALL_DATE',1525756440);
// 序列号
define('DEFAULT_SERIALNUMBER','20180508131400oCWIoa');
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 定义时间
define('NOW_TIME',$_SERVER['REQUEST_TIME']);
// 阿里大鱼的日志存放路径
define('TOP_SDK_WORK_DIR', 'public/upload/tmp/');
// 加载框架引导文件
require __DIR__ . '/core/start.php';
