<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:43:"./application/admin/template/system\web.htm";i:1542350278;s:67:"E:\project\www.yun.com\application\admin\template\public\layout.htm";i:1541056395;s:64:"E:\project\www.yun.com\application\admin\template\system\bar.htm";i:1540957331;s:67:"E:\project\www.yun.com\application\admin\template\public\footer.htm";i:1525273455;}*/ ?>
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
<script type="text/javascript" src="/public/static/admin/js/clipboard.min.js"></script>
<body class="system-web">
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
    <form method="post" id="handlepost" action="<?php echo U('System/web'); ?>" enctype="multipart/form-data" name="form1">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_url">关闭网站</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="web_status1" class="cb-enable <?php if(isset($config['web_status']) AND $config['web_status'] == 1): ?>selected<?php endif; ?>">是</label>
                        <label for="web_status0" class="cb-disable <?php if(!isset($config['web_status']) OR empty($config['web_status'])): ?>selected<?php endif; ?>">否</label>
                        <input id="web_status1" name="web_status" value="1" type="radio" <?php if(isset($config['web_status']) AND $config['web_status'] == 1): ?> checked="checked"<?php endif; ?>>
                        <input id="web_status0" name="web_status" value="0" type="radio" <?php if(!isset($config['web_status']) OR empty($config['web_status'])): ?> checked="checked"<?php endif; ?>>
                    </div>
                    <p class="notic"></p>
                    <?php if($web_cmsmode == '2'): ?>
                    <dd class="variable">
                        <div><p><b>变量名</b></p></div>
                        <div class="r"><b>标签调用</b></div>
                    </dd>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_name">网站名称</label>
                </dt>
                <dd class="opt">
                    <input id="web_name" name="web_name" value="<?php echo (isset($config['web_name']) && ($config['web_name'] !== '')?$config['web_name']:''); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_name</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_name');" class="ui-btn blue web_name" data-clipboard-text="{eyou:global name='web_name' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_logo">网站LOGO</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show div_web_logo_local" <?php if(isset($config['web_logo_is_remote']) AND $config['web_logo_is_remote'] == 1): ?>style="display: none;"<?php endif; ?>>
                        <span class="show">
                            <a id="img_a_web_logo" class="nyroModal" rel="gal" href="<?php echo (isset($config['web_logo_local']) && ($config['web_logo_local'] !== '')?$config['web_logo_local']:'javascript:void(0);'); ?>" target="_blank">
                                <i id="img_i_web_logo" class="fa fa-picture-o" <?php if(!(empty($config['web_logo_local']) || (($config['web_logo_local'] instanceof \think\Collection || $config['web_logo_local'] instanceof \think\Paginator ) && $config['web_logo_local']->isEmpty()))): ?>onmouseover="layer_tips=layer.tips('<img src=<?php echo (isset($config['web_logo_local']) && ($config['web_logo_local'] !== '')?$config['web_logo_local']:''); ?> class=\'layer_tips_img\'>',this,{tips: [1, '#fff']});"<?php endif; ?> onmouseout="layer.close(layer_tips);"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="web_logo_local" name="web_logo_local" value="<?php echo (isset($config['web_logo_local']) && ($config['web_logo_local'] !== '')?$config['web_logo_local']:''); ?>" class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','allimg','web_logo_img_call_back')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <input type="text" id="web_logo_remote" name="web_logo_remote" value="<?php echo (isset($config['web_logo_remote']) && ($config['web_logo_remote'] !== '')?$config['web_logo_remote']:''); ?>" placeholder="http://" class="input-txt" <?php if(!isset($config['web_logo_is_remote']) OR empty($config['web_logo_is_remote'])): ?>style="display: none;"<?php endif; ?>>
                    &nbsp;
                    <label><input type="checkbox" name="web_logo_is_remote" id="web_logo_is_remote" value="1" <?php if(isset($config['web_logo_is_remote']) AND $config['web_logo_is_remote'] == 1): ?>checked="checked"<?php endif; ?> onClick="clickRemote(this, 'web_logo');">远程图片</label>
                    <span class="err"></span>
                    <p class="notic">默认网站LOGO，通用头部显示，显示尺寸以模板设计为主</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_logo</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_logo');" class="ui-btn blue web_logo" data-clipboard-text="{eyou:global name='web_logo' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_ico">地址栏图标</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_a_web_ico" class="nyroModal" rel="gal" href="<?php echo (isset($config['web_ico']) && ($config['web_ico'] !== '')?$config['web_ico']:'javascript:void(0);'); ?>?t=<?php echo time(); ?>" target="_blank">
                                <i id="img_i_web_ico" class="fa fa-picture-o" <?php if(!(empty($config['web_ico']) || (($config['web_ico'] instanceof \think\Collection || $config['web_ico'] instanceof \think\Paginator ) && $config['web_ico']->isEmpty()))): ?>onmouseover="layer_tips=layer.tips('<img src=<?php echo (isset($config['web_ico']) && ($config['web_ico'] !== '')?$config['web_ico']:''); ?>?t=<?php echo time(); ?> width=32 height=32>',this,{tips: [1, '#fff']});"<?php endif; ?> onmouseout="layer.close(layer_tips);"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="web_ico" name="web_ico" value="<?php echo (isset($config['web_ico']) && ($config['web_ico'] !== '')?$config['web_ico']:''); ?>" class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','allimg','web_ico_img_call_back')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">建议尺寸 32 * 32 (像素) 的.ico文件。<br/>如果无法正常显示新上传图标，清空浏览器缓存后访问。</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_ico</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_ico');" class="ui-btn blue web_ico" data-clipboard-text="{eyou:global name='web_ico' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_basehost">网站网址</label>
                </dt>
                <dd class="opt">
                    <input id="web_basehost" name="web_basehost" value="<?php echo (isset($config['web_basehost']) && ($config['web_basehost'] !== '')?$config['web_basehost']:''); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_basehost</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_basehost');" class="ui-btn blue web_basehost" data-clipboard-text="{eyou:global name='web_basehost' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_title">网站标题</label>
                </dt>
                <dd class="opt">
                    <input id="web_title" name="web_title" value="<?php echo (isset($config['web_title']) && ($config['web_title'] !== '')?$config['web_title']:''); ?>" class="input-txt" type="text" />
                    <p class="notic">展现在首页title，有利于SEO</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_title</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_title');" class="ui-btn blue web_title" data-clipboard-text="{eyou:global name='web_title' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_keywords">网站关键词</label>
                </dt>
                <dd class="opt ui-keyword">
                    <input id="web_keywords" name="web_keywords" value="<?php echo (isset($config['web_keywords']) && ($config['web_keywords'] !== '')?$config['web_keywords']:''); ?>" class="input-txt" type="text" />
                    <p class="notic">多个关键词请用逗号,隔开，建议3到4个关键词。</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_keywords</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_keywords');" class="ui-btn blue web_keywords" data-clipboard-text="{eyou:global name='web_keywords' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_description">网站描述</label>
                </dt>
                <dd class="opt ui-text">
                    <textarea rows="5" cols="60" id="web_description" name="web_description" style="height:60px;" class="ui-input" data-num="100"><?php echo (isset($config['web_description']) && ($config['web_description'] !== '')?$config['web_description']:''); ?></textarea>
                    <p class="notic">（<span class="ui-textTips">还可以输入100个字</span>）</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_description</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_description');" class="ui-btn blue web_description" data-clipboard-text="{eyou:global name='web_description' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_copyright">版权信息</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="web_copyright" name="web_copyright" style="height:80px;"><?php echo (isset($config['web_copyright']) && ($config['web_copyright'] !== '')?$config['web_copyright']:''); ?></textarea>
                    <p class="notic"></p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_copyright</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_copyright');" class="ui-btn blue web_copyright" data-clipboard-text="{eyou:global name='web_copyright' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_recordnum">备案号</label>
                </dt>
                <dd class="opt ui-keyword">
                    <input id="web_recordnum" name="web_recordnum" value="<?php echo (isset($config['web_recordnum']) && ($config['web_recordnum'] !== '')?$config['web_recordnum']:''); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_recordnum</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_recordnum');" class="ui-btn blue web_recordnum" data-clipboard-text="{eyou:global name='web_recordnum' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row"><dt class="tit" style="width: auto"><label><b>自定义变量</b></label>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="customvar(this);" data-id="0">[新增]</a>&nbsp;&nbsp;<!-- <a href="javascript:void(0);" onclick="customvar_recycle(this);">[回收站]</a> --></dt></dl>
            <?php if(is_array($eyou_row) || $eyou_row instanceof \think\Collection || $eyou_row instanceof \think\Paginator): $i = 0; $__LIST__ = $eyou_row;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <dl class="row" id="dl_<?php echo $vo['attr_var_name']; ?>">
                <dt class="tit">
                    <label for="<?php echo $vo['attr_var_name']; ?>"><?php echo $vo['attr_name']; ?></label>
                </dt>
                <dd class="opt">
                    <?php switch($vo['attr_input_type']): case "1": break; case "2": ?>
                        <textarea rows="5" cols="60" id="<?php echo $vo['attr_var_name']; ?>" name="<?php echo $vo['attr_var_name']; ?>" style="height:36px;"><?php echo (isset($vo['value']) && ($vo['value'] !== '')?$vo['value']:''); ?></textarea>
                        <?php break; case "3": ?>
                        <div class="input-file-show">
                            <span class="show">
                                <a id="img_a_<?php echo $vo['attr_var_name']; ?>" class="nyroModal" rel="gal" href="<?php echo (isset($vo['value']) && ($vo['value'] !== '')?$vo['value']:'javascript:void(0);'); ?>" target="_blank">
                                    <i id="img_i_<?php echo $vo['attr_var_name']; ?>" class="fa fa-picture-o" <?php if(!(empty($vo['value']) || (($vo['value'] instanceof \think\Collection || $vo['value'] instanceof \think\Paginator ) && $vo['value']->isEmpty()))): ?>onmouseover="layer_tips=layer.tips('<img src=<?php echo (isset($vo['value']) && ($vo['value'] !== '')?$vo['value']:''); ?> class=\'layer_tips_img\'>',this,{tips: [1, '#fff']});"<?php endif; ?> onmouseout="layer.close(layer_tips);"></i>
                                </a>
                            </span>
                            <span class="type-file-box">
                                <input type="text" id="<?php echo $vo['attr_var_name']; ?>" name="<?php echo $vo['attr_var_name']; ?>" value="<?php echo (isset($vo['value']) && ($vo['value'] !== '')?$vo['value']:''); ?>" class="type-file-text">
                                <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                                <input class="type-file-file" onClick="GetUploadify(1,'','allimg','<?php echo $vo['attr_var_name']; ?>_img_call_back')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                            </span>
                        </div>
                        <script type="text/javascript">
                            function <?php echo $vo['attr_var_name']; ?>_img_call_back(fileurl_tmp)
                            {
                                $("#<?php echo $vo['attr_var_name']; ?>").val(fileurl_tmp);
                                $("#img_a_<?php echo $vo['attr_var_name']; ?>").attr('href', fileurl_tmp);
                                $("#img_i_<?php echo $vo['attr_var_name']; ?>").attr('onmouseover', "layer_tips=layer.tips('<img src="+fileurl_tmp+" class=\\'layer_tips_img\\'>',this,{tips: [1, '#fff']});");
                            }
                        </script>
                        <?php break; default: ?>
                        <input id="<?php echo $vo['attr_var_name']; ?>" name="<?php echo $vo['attr_var_name']; ?>" value="<?php echo (isset($vo['value']) && ($vo['value'] !== '')?$vo['value']:''); ?>" class="input-txt" type="text" />
                    <?php endswitch; ?>
                    &nbsp;
                    <a href="javascript:void(0);" onclick="customvar(this);" data-id="<?php echo $vo['id']; ?>" class="ui-btn blue">编辑</a>
                    <a href="javascript:void(0);" onclick="customvar_del(this);" data-id="<?php echo $vo['id']; ?>" data-attr_var_name="<?php echo $vo['attr_var_name']; ?>" class="ui-btn red">删除</a>
                    <?php if($web_cmsmode == '1'): ?>
                    <a href="javascript:void(0);" onclick="showtext('<?php echo $vo['attr_var_name']; ?>');" class="ui-btn blue <?php echo $vo['attr_var_name']; ?>" data-clipboard-text="{<?php  echo 'eyou:global name=\''; ?><?php echo $vo['attr_var_name'];  echo '\' /'; ?>}">复制标签</a>
                    <?php endif; ?>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p><?php echo $vo['attr_var_name']; ?></p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('<?php echo $vo['attr_var_name']; ?>');" class="ui-btn blue <?php echo $vo['attr_var_name']; ?>" data-clipboard-text="{<?php  echo 'eyou:global name=\''; ?><?php echo $vo['attr_var_name'];  echo '\' /'; ?>}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <dl class="row"><dt class="tit"><label><b>网站第三方代码</b></label></dt></dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_thirdcode_pc">电脑PC端</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="web_thirdcode_pc" name="web_thirdcode_pc" style="height:80px;"><?php echo (isset($config['web_thirdcode_pc']) && ($config['web_thirdcode_pc'] !== '')?$config['web_thirdcode_pc']:''); ?></textarea>
                    <p class="notic">代码会放在 &lt;/body&gt; 标签以上（一般用于放置百度商桥代码、站长统计代码、谷歌翻译代码等）</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_thirdcode_pc</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_thirdcode_pc');" class="ui-btn blue web_thirdcode_pc" data-clipboard-text="{eyou:global name='web_thirdcode_pc' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="web_thirdcode_wap">手机移动端</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="web_thirdcode_wap" name="web_thirdcode_wap" style="height:80px;"><?php echo (isset($config['web_thirdcode_wap']) && ($config['web_thirdcode_wap'] !== '')?$config['web_thirdcode_wap']:''); ?></textarea>
                    <p class="notic">代码会放在 &lt;/body&gt; 标签以上（一般用于放置百度商桥代码、站长统计代码、谷歌翻译代码等）</p>
                </dd>
                <?php if($web_cmsmode == '2'): ?>
                <dd class="variable">
                    <div><p>web_thirdcode_wap</p></div>
                    <div class="r"><a href="javascript:void(0);" onclick="showtext('web_thirdcode_wap');" class="ui-btn blue web_thirdcode_wap" data-clipboard-text="{eyou:global name='web_thirdcode_wap' /}">复制标签</a></div>
                </dd>
                <?php endif; ?>
            </dl>
            <div class="bot">
                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="adsubmit();">确认提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    $(function(){
        tipsText();
    });

    function adsubmit(){
        layer_loading("正在处理");
        $('#handlepost').submit();
    }
    
    function web_logo_img_call_back(fileurl_tmp)
    {
        $("#web_logo_local").val(fileurl_tmp);
        $("#img_a_web_logo").attr('href', fileurl_tmp);
        $("#img_i_web_logo").attr('onmouseover', "layer_tips=layer.tips('<img src="+fileurl_tmp+" class=\\'layer_tips_img\\'>',this,{tips: [1, '#fff']});");
    }
    
    function web_ico_img_call_back(fileurl_tmp)
    {
        $("#web_ico").val(fileurl_tmp);
        $("#img_a_web_ico").attr('href', fileurl_tmp);
        $("#img_i_web_ico").attr('onmouseover', "layer_tips=layer.tips('<img src="+fileurl_tmp+" width=32 height=32>',this,{tips: [1, '#fff']});");
    }

    function customvar(obj)
    {
        var id = $(obj).attr('data-id');
        var url = "<?php echo U('System/customvar', array('inc_type'=>'web')); ?>";
        if (url.indexOf('?') > -1) {
            url += '&';
        } else {
            url += '?';
        }
        url += "id="+id;
        //iframe窗
        layer.open({
            type: 2,
            title: '自定义变量',
            fixed: true, //不固定
            shadeClose: false,
            shade: 0.3,
            maxmin: false, //开启最大化最小化按钮
            area: ['700px', '450px'],
            content: url
        });
    }

    function customvar_del(obj) {
        layer.confirm('模板数据将不完整，确认删除？', {
              btn: ['确定','取消'] //按钮
            }, function(){
                // 确定
                layer_loading('正在处理');
                $.ajax({
                    type : 'post',
                    url : "<?php echo U('System/customvar_del'); ?>",
                    data : {del_id:$(obj).attr('data-id')},
                    dataType : 'json',
                    success : function(data){
                        layer.closeAll();
                        if(data.code == 1){
                            layer.msg(data.msg, {icon: 1});
                            window.location.reload();
                            // $('#dl_'+$(obj).attr('data-attr_var_name')).remove();
                        }else{
                            layer.msg(data.msg, {icon: 2,time: 2000});
                        }
                    }
                });
            }, function(index){
                layer.close(index);
                return false;// 取消
            }
        );
    }

    function customvar_recycle(obj)
    {
        var url = "<?php echo U('System/customvar_recycle'); ?>";
        //iframe窗
        layer.open({
            type: 2,
            title: '自定义变量-回收站列表',
            fixed: true, //不固定
            shadeClose: false,
            shade: 0.3,
            maxmin: false, //开启最大化最小化按钮
            area: ['700px', '450px'],
            content: url,
            end: function(){ //结束弹窗之后的处理
                window.location.reload();
            }
        });
    }

    function showtext(classname){
        var clipboard1 = new Clipboard("."+classname);clipboard1.on("success", function(e) {layer.msg("复制成功");});clipboard1.on("error", function(e) {layer.msg("复制失败！请手动复制", {icon:2});}); 
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