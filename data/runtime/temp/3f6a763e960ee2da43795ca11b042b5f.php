<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:44:"./application/admin/template/system\web2.htm";i:1542017655;s:67:"E:\project\www.yun.com\application\admin\template\public\layout.htm";i:1541056395;s:64:"E:\project\www.yun.com\application\admin\template\system\bar.htm";i:1540957331;s:67:"E:\project\www.yun.com\application\admin\template\public\footer.htm";i:1525273455;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/public/static/admin/css/page.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/admin/font/css/font-awesome-ie7.min.css">
<![endif]-->
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
    var GetUploadify_url = "<?php echo U('Uploadify/upload'); ?>";
</script>  
<link href="/public/static/admin/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/admin/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery-ui/jquery-ui.min.js"></script>
<!-- <script type="text/javascript" src="/public/plugins/layer/layer.js"></script> -->
<script type="text/javascript" src="/public/plugins/layer-v3.1.0/layer.js"></script>
<script type="text/javascript" src="/public/static/admin/js/admin.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/admin/js/common.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.mousewheel.js"></script>
<script src="/public/static/admin/js/myFormValidate.js"></script>
<script src="/public/static/admin/js/myAjax2.js?v=<?php echo $version; ?>"></script>
<script src="/public/static/admin/js/global.js?v=<?php echo $version; ?>"></script>

</head>
<body style="background-color: #FFF; overflow: auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
        <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><i class="fa fa-cog"></i>基本信息</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php if(is_check_access(CONTROLLER_NAME.'@web') == '1'): ?>
                <li><a href="<?php echo U('System/web'); ?>" <?php if('web'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>网站设置</span></a></li>
                <?php endif; if(is_check_access(CONTROLLER_NAME.'@web2') == '1'): ?>
                <li><a href="<?php echo U('System/web2'); ?>" <?php if('web2'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>核心设置</span></a></li>
                <?php endif; if(is_check_access(CONTROLLER_NAME.'@basic') == '1'): ?>
                <li><a href="<?php echo U('System/basic'); ?>" <?php if('basic'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>附件设置</span></a></li>
                <?php endif; if(is_check_access(CONTROLLER_NAME.'@water') == '1'): ?>
                <li><a href="<?php echo U('System/water'); ?>" <?php if('water'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>图片水印</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <form method="post" id="handlepost" action="<?php echo U('System/web2'); ?>" enctype="multipart/form-data" name="form1">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">系统模式</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="web_cmsmode2" class="cb-enable <?php if(!isset($config['web_cmsmode']) || $config['web_cmsmode'] == 2): ?>selected<?php endif; ?>" >开发模式</label>
                        <label for="web_cmsmode1" class="cb-disable <?php if(isset($config['web_cmsmode']) && $config['web_cmsmode'] == 1): ?>selected<?php endif; ?>" >运营模式</label>
                        <input id="web_cmsmode2" onClick="set_cmsmode(2)" name="web_cmsmode" value="2" <?php if(!isset($config['web_cmsmode']) || $config['web_cmsmode'] == 2): ?>checked<?php endif; ?> type="radio">
                        <input id="web_cmsmode1" onClick="set_cmsmode(1)" name="web_cmsmode"  value="1" <?php if(isset($config['web_cmsmode']) && $config['web_cmsmode'] == 1): ?>checked<?php endif; ?> type="radio">
                    </div>
                    <br/>
                    <p class="">开发模式：方便修改模板，及时预览前台效果，没缓存，一改便生效。<br/>运营模式：提高前台响应速度，利于收录；改模板及后台发布内容需执行【<a href="<?php echo U('System/clearCache'); ?>">更新缓存</a>】后才能在前台展示。</p>
                </dd>
            </dl>
            <dl class="row <?php if(!isset($config['web_cmsmode']) || $config['web_cmsmode'] != 1): ?>none<?php endif; ?>" id="dl_web_htmlcache_expires_in">
                <dt class="tit">
                    <label for="seo_expires_in">页面缓存时间</label>
                </dt>
                <dd class="opt">
                    <input id="web_htmlcache_expires_in" name="web_htmlcache_expires_in" value="<?php echo (isset($config['web_htmlcache_expires_in']) && ($config['web_htmlcache_expires_in'] !== '')?$config['web_htmlcache_expires_in']:'7200'); ?>" class="input-txt" type="text" />
                    <p class="">以秒为单位，数值越小，等待前台页面缓存自动刷新的时间越短。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_url">升级弹窗提醒</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="web_show_popup_upgrade1" class="cb-enable <?php if(!isset($config['web_show_popup_upgrade']) || $config['web_show_popup_upgrade'] == 1): ?>selected<?php endif; ?>">开启</label>
                        <label for="web_show_popup_upgrade0" class="cb-disable <?php if(isset($config['web_show_popup_upgrade']) && $config['web_show_popup_upgrade'] == -1): ?>selected<?php endif; ?>">关闭</label>
                        <input id="web_show_popup_upgrade1" name="web_show_popup_upgrade" value="1" type="radio" <?php if(!isset($config['web_show_popup_upgrade']) || $config['web_show_popup_upgrade'] == 1): ?> checked="checked"<?php endif; ?>>
                        <input id="web_show_popup_upgrade0" name="web_show_popup_upgrade" value="-1" type="radio" <?php if(isset($config['web_show_popup_upgrade']) && $config['web_show_popup_upgrade'] == -1): ?> checked="checked"<?php endif; ?>>
                    </div>
                    <p class="notic">开启之后，第一时间收到系统更新提醒</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_url">插件应用入口</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="web_weapp_switch1" class="cb-enable <?php if(isset($config['web_weapp_switch']) && $config['web_weapp_switch'] == 1): ?>selected<?php endif; ?>">开启</label>
                        <label for="web_weapp_switch0" class="cb-disable <?php if(!isset($config['web_weapp_switch']) || $config['web_weapp_switch'] == -1): ?>selected<?php endif; ?>">关闭</label>
                        <input id="web_weapp_switch1" name="web_weapp_switch" value="1" type="radio" <?php if(isset($config['web_weapp_switch']) && $config['web_weapp_switch'] == 1): ?> checked="checked"<?php endif; ?>>
                        <input id="web_weapp_switch0" name="web_weapp_switch" value="-1" type="radio" <?php if(!isset($config['web_weapp_switch']) || $config['web_weapp_switch'] == -1): ?> checked="checked"<?php endif; ?>>
                    </div>
                    <p class="notic">开启之后，左侧菜单会显示插件应用入口</p>
                </dd>
            </dl>
            <dl class="row none">
                <dt class="tit">
                    <label for="web_cmspath">系统安装目录</label>
                </dt>
                <dd class="opt">
                    <input id="web_cmspath" name="web_cmspath" value="<?php echo (isset($config['web_cmspath']) && ($config['web_cmspath'] !== '')?$config['web_cmspath']:''); ?>" class="input-txt" type="text" />
                    <p class="notic">目录后面不要带 / 反斜杆，一般适用于EyouCms安装在子目录</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="adminbasefile">新后台地址</label>
                </dt>
                <dd class="opt">http://www.yun.com/<input type="hidden" name="adminbasefile_old" value="<?php echo (isset($adminbasefile) && ($adminbasefile !== '')?$adminbasefile:'login'); ?>"><input id="adminbasefile" name="adminbasefile" value="<?php echo (isset($adminbasefile) && ($adminbasefile !== '')?$adminbasefile:'login'); ?>" type="text" data-site_url="http://www.yun.com" onKeyUp="this.value=this.value.replace(/[^0-9a-zA-Z_-]/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^0-9a-zA-Z_-]/g,''));" style="width: 80px;" />.php
                    <p class="notic">为了提高后台的安全性，请及时更改后台入口文件。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_sqldatapath">数据库备份目录</label>
                </dt>
                <dd class="opt">
                    <input id="web_sqldatapath" name="web_sqldatapath" value="<?php echo (isset($config['web_sqldatapath']) && ($config['web_sqldatapath'] !== '')?$config['web_sqldatapath']:$sqlbackuppath); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
            </dl>
<!--             <dl class="row">
                <dt class="tit">
                    <label for="site_url">启用绝对网址</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="web_multi_site1" class="cb-enable <?php if(isset($config['web_multi_site']) AND $config['web_multi_site'] == 1): ?>selected<?php endif; ?>">是</label>
                        <label for="web_multi_site0" class="cb-disable <?php if(!isset($config['web_multi_site']) OR empty($config['web_multi_site'])): ?>selected<?php endif; ?>">否</label>
                        <input id="web_multi_site1" name="web_multi_site" value="1" type="radio" <?php if(isset($config['web_multi_site']) AND $config['web_multi_site'] == 1): ?> checked="checked"<?php endif; ?>>
                        <input id="web_multi_site0" name="web_multi_site" value="0" type="radio" <?php if(!isset($config['web_multi_site']) OR empty($config['web_multi_site'])): ?> checked="checked"<?php endif; ?>>
                    </div>
                    <p class="notic">启用绝对网址，开启此项后附件、栏目连接、arclist内容等都使用http路径</p>
                </dd>
            </dl> -->
            <div class="bot">
                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onClick="adsubmit();">确认提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function adsubmit(){
        var adminbasefileObj = $('input[name=adminbasefile]');
        var adminbasefile_oldObj = $('input[name=adminbasefile_old]');
        if($.trim(adminbasefileObj.val()) == ''){
            showErrorMsg('新后台地址不能为空！');
            adminbasefileObj.focus();
            return false;
        }
        if(adminbasefile_oldObj.val() != adminbasefileObj.val()){
            var flag = false;
            var site_url = adminbasefileObj.data('site_url');
            layer.confirm('新后台地址：<font color="red">'+site_url+'/'+adminbasefileObj.val()+'.php</font>，确认更改？', {
                    btn: ['继续更改','取消'] //按钮
                }, function(){
                    layer_loading('正在处理');
                    $('#handlepost').submit();
                }, function(index){
                    flag = false;
                }
            );
            return flag;
        }
        layer_loading('正在处理');
        $('#handlepost').submit();
    }

    function set_cmsmode(type){
        if(1 == type){
            $('#dl_web_htmlcache_expires_in').show();
        }else{
            $('#dl_web_htmlcache_expires_in').hide();
        }
    }
</script>

<br/>
<div id="goTop">
    <a href="JavaScript:void(0);" id="btntop">
        <i class="fa fa-angle-up"></i>
    </a>
    <a href="JavaScript:void(0);" id="btnbottom">
        <i class="fa fa-angle-down"></i>
    </a>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#think_page_trace_open').css('z-index', 99999);
    });
</script>
</body>
</html>