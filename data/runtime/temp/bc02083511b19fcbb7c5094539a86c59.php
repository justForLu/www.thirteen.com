<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:44:"./application/admin/template/index\index.htm";i:1550024376;s:68:"E:\project\www.yun.com\application\admin\template\public\menubox.htm";i:1516929058;s:65:"E:\project\www.yun.com\application\admin\template\public\left.htm";i:1541860410;}*/ ?>
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
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" media="screen"/>
<title><?php echo (isset($global['web_name']) && ($global['web_name'] !== '')?$global['web_name']:''); ?>-<?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>拾叁网络科技网站管理系统<?php endif; ?><?php echo $version; ?></title>
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
    var SITEURL = window.location.host + eyou_basefile + "/" + module_name;
    var GetUploadify_url = "<?php echo U('Uploadify/upload'); ?>";
</script>

<link href="/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/public/static/admin/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<script type="text/javascript" src="/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/admin/js/common.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.bgColorSelector.js"></script>
<script type="text/javascript" src="/public/static/admin/js/admincp.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.validation.min.js"></script>

<!-- <script type="text/javascript" src="/public/plugins/layer/layer.js"></script> -->
<script type="text/javascript" src="/public/plugins/layer-v3.1.0/layer.js"></script>
<script type="text/javascript" src="/public/static/admin/js/dialog/dialog.js" id="dialog_js"></script>
<script src="/public/static/admin/js/upgrade.js?v=<?php echo $version; ?>"></script>
<script src="/public/static/admin/js/global.js?v=<?php echo $version; ?>"></script>
</head>
<body>

  <script type="text/javascript">
  //固定层移动
  $(function(){
      //管理显示与隐藏
      $('img[tptype="admin_avatar"], #admin-manager-btn').click(function () {
          if ($(".manager-menu").css("display") == "none") {
              $(".manager-menu").css('display', 'block'); 
              $("#admin-manager-btn").attr("title","关闭快捷管理"); 
              $("#admin-manager-btn").removeClass().addClass("arrow-close");
          }
          else {
              $(".manager-menu").css('display', 'none');
              $("#admin-manager-btn").attr("title","显示快捷管理");
              $("#admin-manager-btn").removeClass().addClass("arrow");
          }           
      });
  });
  </script> 
<style>
.scroll-wrapper {   
  height: 100%;
  -webkit-overflow-scrolling: touch;   
  overflow-y: auto;   
}  
</style>
<div class="admincp-container unfold">
<div class="metcms_cont_left hidden-xs">
    <dl class="metlogo">
        <?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>
        <a href="javascript:void(0);" onclick="openItem('Index|welcome');" target="workspace"><img src="/public/static/admin/images/logo_ey.png?v=<?php echo time(); ?>" alt="<?php echo (isset($global['web_name']) && ($global['web_name'] !== '')?$global['web_name']:''); ?>" title="<?php echo (isset($global['web_name']) && ($global['web_name'] !== '')?$global['web_name']:''); ?>"></a>
        <?php else: ?>
        <a href="javascript:void(0);"><img src="/public/static/admin/images/logo.png?v=<?php echo time(); ?>" alt="<?php echo (isset($global['web_name']) && ($global['web_name'] !== '')?$global['web_name']:''); ?>" title="<?php echo (isset($global['web_name']) && ($global['web_name'] !== '')?$global['web_name']:''); ?>" onClick="GetUploadify(1,'','adminlogo','adminlogo_call_back','<?php echo U('Uploadify/upload_full'); ?>');" size="30" hidefocus="true"></a>
        <?php endif; ?>
    </dl>
    <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    <dl>
    <!--<dt>内容</dt>-->
        <?php if(!(empty($vo['name']) || (($vo['name'] instanceof \think\Collection || $vo['name'] instanceof \think\Paginator ) && $vo['name']->isEmpty()))): ?><dt><?php echo $vo['name']; ?></dt><?php endif; ?>
        <dd class="sub-menu">
          <?php if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $k2 = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($k2 % 2 );++$k2;if(!empty($v2['controller']) OR !empty($v2['url']) OR !empty($vo2['child'])): if(empty($v2['child']) || (($v2['child'] instanceof \think\Collection || $v2['child'] instanceof \think\Paginator ) && $v2['child']->isEmpty())): ?>
                <a href="<?php echo (isset($v2['url']) && ($v2['url'] !== '')?$v2['url']:'javascript:void(0);'); ?>" <?php if(empty($v2['url']) || (($v2['url'] instanceof \think\Collection || $v2['url'] instanceof \think\Paginator ) && $v2['url']->isEmpty())): ?>data-param="<?php echo $v2['controller']; ?>|<?php echo $v2['action']; ?>"<?php endif; ?> target="<?php echo $v2['target']; ?>"><i class="<?php echo (isset($v2['icon']) && ($v2['icon'] !== '')?$v2['icon']:'fa fa-minus'); ?>"></i><?php echo $v2['name']; ?></a>
                <?php else: ?>
                <dl class="jslist" style="border-bottom:none;">
                    <dt><i class="<?php echo (isset($v2['icon']) && ($v2['icon'] !== '')?$v2['icon']:'fa fa-minus'); ?>"></i><?php echo $v2['name']; ?><i class="fa fa-angle-right"></i><?php if($v2['controller'] == 'Senior' and !empty($upgradeMsg)): endif; ?></dt>
                    <dd style="display: none;">
                        <?php if(is_array($v2['child']) || $v2['child'] instanceof \think\Collection || $v2['child'] instanceof \think\Paginator): $k3 = 0; $__LIST__ = $v2['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v3): $mod = ($k3 % 2 );++$k3;if(!empty($v3['controller']) OR !empty($v3['url']) OR !empty($v3['child'])): ?>
                            <a href="<?php echo (isset($v3['url']) && ($v3['url'] !== '')?$v3['url']:'javascript:void(0);'); ?>" <?php if(empty($v3['url']) || (($v3['url'] instanceof \think\Collection || $v3['url'] instanceof \think\Paginator ) && $v3['url']->isEmpty())): ?>data-param="<?php echo $v3['controller']; ?>|<?php echo $v3['action']; ?>"<?php endif; ?> target="<?php echo $v3['target']; ?>"><i class="<?php echo (isset($v3['icon']) && ($v3['icon'] !== '')?$v3['icon']:'fa fa-minus'); ?>"></i><?php echo $v3['name']; if($v3['controller'] == 'Upgrade' and !empty($upgradeMsg)): endif; ?></a>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </dd>
                </dl>
                <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
        </dd>
    </dl>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</div>

<script type="text/javascript">
    function adminlogo_call_back(fileurl_tmp)
    {
        layer_loading("正在处理");
        var url = "<?php echo U('Index/edit_adminlogo'); ?>";
        $.post(url, {savepath:'adminlogo' ,filename:fileurl_tmp},function(res){
            if (1 == res.code) {
                layer.msg(res.msg, {icon:6, time:1000}, function(){
                    top.window.location.reload();
                });
            } else {
                layer.closeAll();
                layer.alert(res.msg, {icon:5});
            }
        });
    }
</script>
  <div class="admincp-container-right">
    <div class="admincp-header">
      <div class="wraper">
        <div class="bgSelector"></div>
        <div id="foldSidebar"><i  title="展开/收起侧边导航"></i></div>
        <div class="admincp-name" id="foldSidebar2">
        </div>
        <div class="admincp-header-r">
          <div class="manager">
            <dl>
              <dt class="name"><?php echo $admin_info['user_name']; ?></dt>
              <dd class="group"><?php echo $admin_info['role_name']; ?></dd>
            </dl>
            <div class="btn-group pull-left met-tool">
              <a class="btn btn-default dropdown-toggle" target="_blank" href="<?php echo SITE_URL; ?>">
                <i class="fa fa-home"></i>
                <span class="hidden-xs">网站首页</span>
              </a>
              <?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>
              <!-- 商业授权 -->   
              <em class="em_authortoken">
              <a class="btn btn-default dropdown-toggle" title="购买后可去除所有版权提示" href="javascript:void(0);" onclick="$('.em_authortoken').toggleClass('open');">
                  <i class="fa fa-bookmark"></i>
                  <span class="hidden-xs">购买授权</span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="shouquan">
                    <li class="met-tool-list text-center"><a target="_blank" class="liaojie" href="http://www.eyoucms.com/buy/">了解商业授权</a></li>
                    <li class="met-tool-list text-center">
                      <input class="btn btn-primary" type="button" onclick="$('.em_authortoken').toggleClass('open');openItem('Index|authortoken');" value="录入商业授权" />
                    </li>
                </ul>
               </em>
              <!-- 商业授权 -->   
              <?php endif; ?>

              <!-- 服务器升级 -->   
              <textarea id="textarea_filelist" style="display:none;"></textarea> 
              <textarea id="textarea_intro" style="display:none;"></textarea> 
              <textarea id="textarea_notice" style="display:none;"></textarea> 
              <a class="btn btn-default dropdown-toggle" style="display: none;color:#F00;" title="不升级可能有安全隐患" href="javascript:void(0);" id="a_upgrade" data-version="" data-max_version="" data-iframe="parent" data-tips_url="<?php echo U('Upgrade/setPopupUpgrade'); ?>" data-upgrade_url="<?php echo U('Upgrade/OneKeyUpgrade'); ?>">
                <i class="fa fa-info-circle"></i>
                <span class="hidden-xs">系统更新</span>
              </a>
              <!-- 服务器升级 end -->
              
            </div>
            <span class="avatar">
            <img alt="" tptype="admin_avatar" src="/public/static/admin/images/admint.png" style="cursor: pointer;"> </span><i class="arrow" id="admin-manager-btn" title="显示快捷管理菜单"></i>
            <div class="manager-menu">
              <div class="title">
                <h4>最后登录</h4>
                <a href="javascript:void(0);" onClick="CUR_DIALOG = ajax_form('modifypw', '修改密码', '<?php echo U('Admin/admin_pwd',array('admin_id'=>$admin_info['admin_id'])); ?>');" class="edit-password" title="修改密码">修改密码</a> </div>
              <div class="login-date"> <?php echo date('Y-m-d H:i:s',session('admin_info.last_login'));?> <span>(IP: <?php echo session('admin_info.last_ip');?> )</span> </div>
              <div class="title">
                <h4>常用操作</h4>
                <!-- <a href="javascript:void(0);" onClick="openItem('Admin|modular')" class="add-menu">新增菜单</a> -->
              </div>
              <ul class="nc-row" tptype="quick_link">
                  <li><a href="javascript:void(0);" onClick="openItem('System|web');" title="站点设置">站点设置</a></li>
                  <li><a href="<?php echo U('Admin/logout'); ?>" title="安全退出">安全退出</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="top-border"></div>
    <div class="scroll-wrapper">
      <iframe src="" id="workspace" name="workspace" style="overflow-y: auto;" frameborder="0" width="100%" height="94%" scrolling="yes" onload="window.parent"></iframe>
    </div>
  </div>
</div>
</body>
</html>