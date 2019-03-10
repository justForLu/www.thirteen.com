<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/template/index\welcome.htm";i:1550025634;s:67:"E:\project\www.yun.com\application\admin\template\public\footer.htm";i:1525273455;}*/ ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="/public/static/admin/css/index.css" rel="stylesheet" type="text/css">
    <link href="/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/public/static/admin/js/jquery.js"></script>
    <!-- <script type="text/javascript" src="/public/plugins/layer/layer.js"></script> -->
    <script type="text/javascript" src="/public/plugins/layer-v3.1.0/layer.js"></script>
    <script type="text/javascript">
        var module_name = "<?php echo MODULE_NAME; ?>";
        var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    </script>
    <script src="/public/static/admin/js/upgrade.js?v=<?php echo $version; ?>"></script>
    <style>
        .contentWarp_item .section_select .item_comment{
            padding: 83px 0 31px 38px;
        }
        .contentWarp_item .section_select .item {
            padding: 83px 0 38px 38px;
        }
        .contentWarp_item .section_order_select li{
            width: 23%;
        }
    </style>
</head>
<body class="iframe_body">
<div class="warpper">
    <div class="title">管理中心</div>
    <div class="content start_content">
        <div class="contentWarp">
            <div class="index_box">
                <section class="index_point hidden-xs">
                    <h3>快捷入口</h3>
                    <div class="container-fluid">
                        <ul>
                            <?php if(is_check_access('System@index') == '1'): ?>
                            <li>
                                <a href="<?php echo U('System/index', array('inc_type'=>'web')); ?>">
                                    <i class="fa fa-newspaper-o"></i>
                                    基本信息
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Arctype@index') == '1'): ?>
                            <li>
                                <a href="<?php echo U('Arctype/index'); ?>" >
                                    <i class="fa fa-sitemap"></i>
                                    栏目管理
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Filemanager@index') == '1'): ?>
                            <li>
                                <a href="<?php echo U('Filemanager/index'); ?>">
                                    <i class="fa fa-folder-open"></i>
                                    模板管理
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Archives@release') == '1'): ?>
                            <li>
                                <a href="<?php echo U('Archives/release'); ?>">
                                    <i class="fa fa-plus"></i>
                                    发布内容
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; ?>
                            <li>
                                <div class="">
                                    <a href="javascript:void(0);" class="bds_more bdsharebuttonbox" data-cmd="more">
                                        <i class="fa fa-share-alt"></i>分享成果
                                    </a>
                                </div>
                                <script>
                                    window._bd_share_config = {
                                        "common":{
                                            "bdSnsKey":{},
                                            "bdText":"<?php echo $share['bdText']; ?>",
                                            "bdMini":"2",
                                            "bdMiniList":false,
                                            "bdPic":"",
                                            "bdStyle":"2",
                                            "bdSize":"32",
                                            "bdUrl":"<?php echo $share['bdUrl']; ?>"
                                        },
                                        "share":{}
                                    };
                                    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='public/plugins/share/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
                                </script>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="section system_section" style="float: none;width: inherit;">
                <div class="system_section_con">
                    <div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">
                        <i class="sc_icon"></i>
                        <h3>程序信息</h3>
                    </div>
                    <div class="sc_warp" id="system_warp" style="display: block;">
                        <table cellpadding="0" cellspacing="0" class="system_table">
                            <tbody>
                                <tr>
                                    <td class="gray_bg">系统更新：</td>
                                    <td>
                                        <?php if(!(empty($upgradeMsg) || (($upgradeMsg instanceof \think\Collection || $upgradeMsg instanceof \think\Paginator ) && $upgradeMsg->isEmpty()))): ?>
                                          <textarea id="textarea_filelist" style="display:none;"><?php echo $upgradeMsg['upgrade']; ?></textarea> 
                                          <textarea id="textarea_intro" style="display:none;"><?php echo $upgradeMsg['intro']; ?></textarea> 
                                          <textarea id="textarea_notice" style="display:none;"><?php echo $upgradeMsg['notice']; ?></textarea> 
                                          <a href="javascript:void(0);" id="a_upgrade" data-version="<?php echo $upgradeMsg['key_num']; ?>" data-max_version="<?php echo $upgradeMsg['max_version']; ?>" data-iframe="workspace" title="<?php echo $upgradeMsg['tips']; ?>" data-tips_url="<?php echo U('Upgrade/setPopupUpgrade'); ?>" data-upgrade_url="<?php echo U('Upgrade/OneKeyUpgrade'); ?>" style="color:#F00;">检测到新版本<?php echo $upgradeMsg['key_num']; ?>[点击查看]</a>
                                          <?php if(-1 != $web_show_popup_upgrade AND (0 >= \think\Session::get('admin_info.role_id') OR 1 == \think\Session::get('admin_info.auth_role_info.online_update'))): ?>
                                          <script type="text/javascript">
                                            $(document).ready(function(){
                                                btn_upgrade($("#a_upgrade"), 1);
                                            });
                                          </script>
                                          <?php endif; else: ?>
                                        已是最新版
                                        <?php endif; ?>
                                    </td>
                                    <td class="gray_bg">当前版本：</td>
                                    <td><?php echo (isset($sys_info['curent_version']) && ($sys_info['curent_version'] !== '')?$sys_info['curent_version']:'v1.0'); ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg">程序名称：</td>
                                    <td><?php echo (isset($sys_info['web_name']) && ($sys_info['web_name'] !== '')?$sys_info['web_name']:'拾叁网络科技网站管理系统'); ?></td>
                                    <td class="gray_bg">版权所有：</td>
                                    <td><?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>盗版必究<?php else: ?>正版软件<?php endif; ?></td>
                                </tr>
                                <?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>
                                <tr>
                                    <td class="gray_bg">更新日志：</td>
                                    <td><a href="http://www.eyoucms.com/news/rizhi/" target="_blank">查看</a></td>
                                    <td class="gray_bg">帮助中心:</td>
                                    <td><a href="http://www.eyoucms.com/bbs/" target="_blank">查看</a></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="system_section_con">
                    <div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">
                        <i class="sc_icon"></i>
                        <h3>
                            服务器信息
                        </h3>
                    </div>
                    <div class="sc_warp" id="system_warp" style="display: block;padding-bottom: 20px;">
                        <table cellpadding="0" cellspacing="0" class="system_table">
                            <tbody><tr>
                                <td class="gray_bg">服务器操作系统：</td>
                                <td><?php echo $sys_info['os']; ?></td>
                                <td class="gray_bg">服务器域名/IP：</td>
                                <td><?php echo $sys_info['domain']; ?> [ <?php echo $sys_info['ip']; ?> ]</td>
                            </tr>
                            <tr>
                                <td class="gray_bg">服务器环境：</td>
                                <td><?php echo $sys_info['web_server']; ?></td>
                                <td class="gray_bg">PHP 版本：</td>
                                <td><?php echo $sys_info['phpv']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">Mysql 版本：</td>
                                <td><?php echo $sys_info['mysql_version']; ?></td>
                                <td class="gray_bg">GD 版本：</td>
                                <td><?php echo $sys_info['gdinfo']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">文件上传限制：</td>
                                <td><?php echo $sys_info['fileupload']; ?></td>
                                <td class="gray_bg">最大占用内存：</td>
                                <td><?php echo $sys_info['memory_limit']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">POST限制：</td>
                                <td><?php echo (isset($sys_info['postsize']) && ($sys_info['postsize'] !== '')?$sys_info['postsize']:'unknown'); ?></td>
                                <td class="gray_bg">最大执行时间：</td>
                                <td><?php echo $sys_info['max_ex_time']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">Zip支持：</td>
                                <td><?php echo $sys_info['zip']; ?></td>
                                <td class="gray_bg">Zlib支持：</td>
                                <td><?php echo $sys_info['zlib']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer" style="position: static; bottom: 0px; font-size:14px;">
    <p>
        <b><?php echo htmlspecialchars_decode($global['web_copyright']); ?></b>
    </p>
</div>
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