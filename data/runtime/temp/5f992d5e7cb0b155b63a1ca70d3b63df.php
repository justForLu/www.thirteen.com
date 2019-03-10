<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:24:"./template/pc/\index.htm";i:1531879070;s:45:"E:\project\www.yun.com\template\pc\header.htm";i:1531828400;s:45:"E:\project\www.yun.com\template\pc\footer.htm";i:1531898746;}*/ ?>
<!DOCTYPE html>
<html>
  <head>
  <title><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_title"); echo $_value; ?></title>
  <meta name="renderer" content="webkit" />
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=0,minimal-ui" />
  <meta name="description" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_description"); echo $_value; ?>" />
  <meta name="keywords" content="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_keywords"); echo $_value; ?>" />
  <meta name="generator" content="eyoucms" data-variable="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_eyoucms"); echo $_value; ?>" />
  <link href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_cmspath"); echo $_value; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link rel="stylesheet" type="text/css" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/css/basic.css" />
  <link rel="stylesheet" type="text/css" href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/css/common.css" />
  <style>
body {
  background-position: center;
  background-repeat: no-repeat;
background-color:;
font-family:;
}
</style>
  <!--[if lte IE 9]>
    <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/lteie9.js"></script>
    <![endif]-->
  </head>
  <!--[if lte IE 8]>
    <div class="text-xs-center m-b-0 bg-blue-grey-100 alert">
    <button type="button" class="close" aria-label="Close" data-dismiss="alert">
        <span aria-hidden="true">×</span>
    </button>
    你正在使用一个 <strong>过时</strong> 的浏览器。请 <a href=https://browsehappy.com/ target=_blank>升级您的浏览器</a>，以提高您的体验。</div>
<![endif]-->

  <body>
<!--header-s--> 
  <header class="met-head" e-page="header"> 
   <nav class="navbar navbar-default box-shadow-none met-nav"> 
    <div class="container"> 
     <div class="row"> 
      <h1 hidden=""><?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_name"); echo $_value; ?></h1> 
      <div class="navbar-header pull-xs-left"> 
       <a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_cmsurl"); echo $_value; ?>/" class="navbar-logo vertical-align block pull-xs-left" title="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_name"); echo $_value; ?>"> 
        <div class="vertical-align-middle"> 
         <img src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_logo"); echo $_value; ?>" alt="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_name"); echo $_value; ?>" /> 
        </div> </a> 
      </div>
      <button type="button" class="navbar-toggler hamburger hamburger-close collapsed p-x-5 met-nav-toggler" data-target="#met-nav-collapse" data-toggle="collapse">
        <span class="sr-only"></span>
        <span class="hamburger-bar"></span>
      </button> 
      <div class="collapse navbar-collapse navbar-collapse-toolbar pull-md-right p-0" id="met-nav-collapse"> 
       <ul class="nav navbar-nav navlist"> 
        <li class="nav-item"> <a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_cmsurl"); echo $_value; ?>/" title="网站首页" class="nav-link <?php if(CONTROLLER_NAME == 'Index'): ?>active<?php endif; ?>">网站首页</a> </li> 

        <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 60; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "top", "active"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
        <li class="nav-item dropdown m-l-10"> <a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>" class="nav-link dropdown-toggle <?php echo $field['currentstyle']; ?>" <?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?>data-toggle="dropdown"<?php endif; ?> data-hover="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $field['typename']; if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?><span class="fa fa-angle-down p-l-5"></span><?php endif; ?></a> 
         <?php if(!(empty($field['children']) || (($field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator ) && $field['children']->isEmpty()))): ?>
         <div class="dropdown-menu dropdown-menu-right dropdown-menu-bullet"> 
          <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif;if(is_array($field['children']) || $field['children'] instanceof \think\Collection || $field['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field['children']) ? array_slice($field['children'],0,100, true) : $field['children']->slice(0,100, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field2): $field2["typename"] = msubstr($field2["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field2;$mod = ($e % 2 );$i= intval($key) + 1;?>
          <div class="dropdown-item dropdown-submenu"> 
           <a href="<?php echo $field2['typeurl']; ?>" class="dropdown-item "><?php echo $field2['typename']; ?></a> 
           <?php if(!(empty($field2['children']) || (($field2['children'] instanceof \think\Collection || $field2['children'] instanceof \think\Paginator ) && $field2['children']->isEmpty()))): ?>
           <div class="dropdown-menu animate"> 
            <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif;if(is_array($field2['children']) || $field2['children'] instanceof \think\Collection || $field2['children'] instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($field2['children']) ? array_slice($field2['children'],0,100, true) : $field2['children']->slice(0,100, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field3): $field3["typename"] = msubstr($field3["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field3;$mod = ($e % 2 );$i= intval($key) + 1;?>
            <a href="<?php echo $field3['typeurl']; ?>" class="dropdown-item "><?php echo $field3['typename']; ?></a> 
            <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
           </div> 
           <?php endif; ?>
          </div> 
          <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
         </div>
         <?php endif; ?>
        </li> 
        <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
       </ul> 
      </div> 
     </div> 
    </div> 
   </nav> 
  </header>  
<!--header-e--> 
<!--main-s-->
  <div class="met-banner carousel slide" id="exampleCarouselDefault" data-ride="carousel" m-id="banner" m-type="banner" e-page="index"> 
   <ol class="carousel-indicators carousel-indicators-fall"> 
      <?php  $tagAdv = new \think\template\taglib\eyou\TagAdv; $_result = $tagAdv->getAdv(1, "", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, 10, true) : $_result->slice(0, 10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field):  if ($i == 0) : $field["currentstyle"] = "active"; else:  $field["currentstyle"] = ""; endif;$mod = ($e % 2 );$i= intval($key) + 1;?>
      <li data-slide-to="<?php echo $key; ?>" data-target="#exampleCarouselDefault" class="<?php echo $field['currentstyle']; ?>"></li>
      <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
   </ol> 
   <div class="carousel-inner" role="listbox"> 
    <?php  $tagAdv = new \think\template\taglib\eyou\TagAdv; $_result = $tagAdv->getAdv(1, "", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, 10, true) : $_result->slice(0, 10, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field):  if ($i == 0) : $field["currentstyle"] = "active"; else:  $field["currentstyle"] = ""; endif;$mod = ($e % 2 );$i= intval($key) + 1;?>
    <div class="carousel-item eyou-edit <?php echo $field['currentstyle']; ?>" e-id="<?php echo $field['id']; ?>" e-type="adv">
      <a href="<?php echo $field['links']; ?>" title="<?php echo $field['title']; ?>" <?php echo $field['target']; ?>><img class="w-full" src="<?php echo $field['litpic']; ?>" srcset="<?php echo $field['litpic']; ?> 767w,<?php echo $field['litpic']; ?>" sizes="(max-width: 767px) 767px" pch="0" adh="0" iph="0" /></a>
    </div>
    <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
    <a class="left carousel-control" href="#exampleCarouselDefault" role="button" data-slide="prev"> <span class="icon" aria-hidden="true">&lt;</span> <span class="sr-only">Previous</span> </a>
    <a class="right carousel-control" href="#exampleCarouselDefault" role="button" data-slide="next"> <span class="icon" aria-hidden="true">&gt;</span> <span class="sr-only">Next</span> </a>
   </div> 
  </div>
    <main class="met-index-body" e-page="index">
     <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = "3"; endif; if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif; $tagChannelartlist = new \think\template\taglib\eyou\TagChannelartlist; $_result = $tagChannelartlist->getChannelartlist($typeid, "self"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$channelartlist): $channelartlist["typename"] = msubstr($channelartlist["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $channelartlist;$mod = ($e % 2 );$i= intval($key) + 1;?>
      <section class="service_list_met_11_3 text-xs-center">
        <div class="container">
        <div class="title-box clearfix">
            <div class="head">
            <h2 class="title newpro"> <?php  $__TMP__ = explode("|", "typename"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?></h2>
            <p class="desc newpro"></p>
          </div>
            <ul class="tabs ulstyle">
            <li class="active newpro"> <a href=" <?php  $__TMP__ = explode("|", "typeurl"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" title=" <?php  $__TMP__ = explode("|", "typename"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" target="_self">
              <h3>全部</h3>
              </a> </li>
            <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 100; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "son", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
            <li class="newpro"> <a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>" target="_self">
              <h3><?php echo $field['typename']; ?></h3>
              </a> </li>
            <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?> 
          </ul>
          </div>
        <ul class="list m-0 clearfix ulstyle">
          <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 3; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = msubstr($field["title"], 0, 30, false);$field["seo_description"] = msubstr($field["seo_description"], 0, 25, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
            <li class="item col-xs-12 col-md-6 col-lg-4 col-xxl-4 newpro">
            <div class="content"> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <img data-original="<?php echo $field['litpic']; ?>" alt="<?php echo $field['title']; ?>" src="<?php echo $field['litpic']; ?>" class="" style="display: inline;">
              <h4><?php echo $field['title']; ?></h4>
              <p><?php echo $field['seo_description']; ?>...</p>
              </a> </div>
          </li>
          <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
      </ul>
        <a href=" <?php  $__TMP__ = explode("|", "typeurl"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" title=" <?php  $__TMP__ = explode("|", "typename"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" target="_self" class="btn-more">查看更多<i class="fa fa-angle-right"></i> </a> </div>
      </section>
    <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $typeid = $row = ""; unset($channelartlist);  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = "8"; endif; if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 10; endif; $tagChannelartlist = new \think\template\taglib\eyou\TagChannelartlist; $_result = $tagChannelartlist->getChannelartlist($typeid, "self"); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$channelartlist): $channelartlist["typename"] = msubstr($channelartlist["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $channelartlist;$mod = ($e % 2 );$i= intval($key) + 1;?>
    <section class="about_list_met_11_3">
      <div class="background">
        <div style="height: 505px;" class="eyou-edit" e-id="206" e-type="upload">
         <?php  $tagUiupload = new \think\template\taglib\eyou\TagUiupload; $__LIST__ = $tagUiupload->getUiupload("206", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
          <?php echo $field['value']; else: ?>
          <img style="display: inline;" src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/images/bf4ba9853f4435e3e1aa2b43f1da1ba8.jpg" class="">
        <?php endif; ?>
        </div>
      </div>
      <div class="container">
        <div class="about-us col-md-12">
          <div class="head">
            <h2 class="title newpro"> <?php  $__TMP__ = explode("|", "typename"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?></h2>
            <p class="desc newpro"></p>
          </div>
          <div class="content">
            <div class="left col-md-5 col-sm-5">
              <div class="img eyou-edit" e-id="208" e-type="upload">
                 <?php  $tagUiupload = new \think\template\taglib\eyou\TagUiupload; $__LIST__ = $tagUiupload->getUiupload("208", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
                  <?php echo $field['value']; else: ?>
                  <img style="display: inline;" src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/images/1516784706.jpg" class="">
                <?php endif; ?>
              </div>
            </div>
            <div class="right">
              <div class="text" style="visibility: inherit; opacity: 1;">
                <p class="desc newpro">
               <span>
                <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", "single_content"); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
                  <?php echo html_msubstr($field['content'],0,135); ?>...
                <?php endif; else: echo htmlspecialchars_decode("");endif; ?>
               </span>
                <a href=" <?php  $__TMP__ = explode("|", "typeurl"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" title=" <?php  $__TMP__ = explode("|", "typename"); $__TMP_VALUE__ = isset($channelartlist[$__TMP__[0]]) ? $channelartlist[$__TMP__[0]] : "缺少参数值"; foreach($__TMP__ as $key => $func) :  if ($key == 0) : continue; endif;  $__TMP_VALUE__ = $func($__TMP_VALUE__); endforeach; echo $__TMP_VALUE__; ?>" class="btn-more" target="_blank">查看更多<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $typeid = $row = ""; unset($channelartlist); ?>

    <div class="met-index-news">
        <div class="container">
        <div class="col-lg-4 col-md-4 col-xs-12 eyou-edit" e-id="203" e-type="arclist">
            <ul class="list-group">
             <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("11","203", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("默认值");else: $field = $__LIST__;?>
            <li class="list-group-item active clearfix">
                <h4 class="pull-xs-left m-y-0"><?php echo $field['typename']; ?></h4>
                <a href="<?php echo $field['typeurl']; ?>" class="pull-xs-right eyou-edit" e-id='2030' e-type='text'>  <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("2030", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $vo = $__LIST__; ?>
              <?php echo $vo['value']; else: ?>
              MORE
              <?php endif; ?> </a> </li>
            <?php endif; else: echo htmlspecialchars_decode("默认值");endif;  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 4; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = msubstr($field["title"], 0, 30, false);$field["seo_description"] = msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
            <li class="list-group-item news-li clearfix"> <span><?php echo MyDate('Y-m-d',$field['add_time']); ?></span> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <?php echo $field['title']; ?> </a> </li>
            <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
          </ul>
          </div>
        <div class="col-lg-4 col-md-4 col-xs-12 eyou-edit" e-id="204" e-type="arclist">
            <ul class="list-group">
             <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("12","204", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("默认值");else: $field = $__LIST__;?>
            <li class="list-group-item active clearfix">
                <h4 class="pull-xs-left m-y-0"><?php echo $field['typename']; ?></h4>
                <a href="<?php echo $field['typeurl']; ?>" class="pull-xs-right eyou-edit" e-id='2040' e-type='text'>  <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("2040", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $vo = $__LIST__; ?>
              <?php echo $vo['value']; else: ?>
              MORE
              <?php endif; ?> </a> </li>
            <?php endif; else: echo htmlspecialchars_decode("默认值");endif;  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 4; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = msubstr($field["title"], 0, 30, false);$field["seo_description"] = msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
            <li class="list-group-item news-li clearfix"> <span><?php echo MyDate('Y-m-d',$field['add_time']); ?></span> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <?php echo $field['title']; ?> </a> </li>
            <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
          </ul>
          </div>
        <div class="col-lg-4 col-md-4 col-xs-12 eyou-edit" e-id="205" e-type="arclist">
            <ul class="list-group">
             <?php  $tagUiarclist = new \think\template\taglib\eyou\TagUiarclist; $__LIST__ = $tagUiarclist->getUiarclist("3","205", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("默认值");else: $field = $__LIST__;?>
            <li class="list-group-item active clearfix">
                <h4 class="pull-xs-left m-y-0"><?php echo $field['typename']; ?></h4>
                <a href="<?php echo $field['typeurl']; ?>" class="pull-xs-right eyou-edit" e-id='2050' e-type='text'>  <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("2050", "index"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $vo = $__LIST__; ?>
              <?php echo $vo['value']; else: ?>
              MORE
              <?php endif; ?> </a> </li>
            <?php endif; else: echo htmlspecialchars_decode("默认值");endif;  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 4; endif; $channeltype = ""; $param = array(      "typeid"=> $typeid,      "notypeid"=> "",      "flag"=> "",      "noflag"=> "",      "channel"=> $channeltype, ); $tagArclist = new \think\template\taglib\eyou\TagArclist; $_result = $tagArclist->getArclist($param, $row, "", "");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $aid = $field["aid"];$field["title"] = msubstr($field["title"], 0, 30, false);$field["seo_description"] = msubstr($field["seo_description"], 0, 160, true);$mod = ($e % 2 );$i= intval($key) + 1;?>
            <li class="list-group-item news-li clearfix"> <span><?php echo MyDate('Y-m-d',$field['add_time']); ?></span> <a href="<?php echo $field['arcurl']; ?>" title="<?php echo $field['title']; ?>" target="_self"> <?php echo $field['title']; ?> </a> </li>
            <?php ++$e; $aid = 0; endforeach; endif; else: echo htmlspecialchars_decode("");endif; $ui_typeid = $ui_row = ""; endif; ?>
          </ul>
          </div>
      </div>
      </div>
    </div>
  </main>
<!--main-e--> 
<!--footer-s--> 
  <footer class="met-foot-info p-y-20 border-top1" e-page="footer"> 
   <div class="langcss text-xs-center p-b-20"> 
    <div class="container"> 
     <div class="row mob-masonry"> 
      <div class="col-lg-2 col-md-3 col-xs-6 list masonry-item foot-nav eyou-edit" e-id="301" e-type="channel" id="testtest"> 
       <?php  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("1","301", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
       <h4 class="font-size-16 m-t-0"> <a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </h4>
        <?php endif; else: echo htmlspecialchars_decode("");endif; ?>
       <ul class="ulstyle m-b-0"> 
        <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 3; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "son", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
        <li> <a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </li> 
        <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
       </ul> 
      <?php $ui_typeid = $ui_row = ""; endif; ?>
      </div> 
      <div class="col-lg-2 col-md-3 col-xs-6 list masonry-item foot-nav eyou-edit" e-id="302" e-type="channel"> 
       <?php  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("2","302", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
        <h4 class="font-size-16 m-t-0"> <a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </h4>
        <?php endif; else: echo htmlspecialchars_decode("");endif; ?>
       <ul class="ulstyle m-b-0"> 
        <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 3; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "son", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
        <li> <a href="<?php echo $field['typeurl']; ?>" target="_self" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </li> 
        <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
       </ul> 
      <?php $ui_typeid = $ui_row = ""; endif; ?>
      </div> 
      <div class="col-lg-2 col-md-3 col-xs-6 list masonry-item foot-nav eyou-edit" e-id="303" e-type="channel"> 
       <?php  $tagUichannel = new \think\template\taglib\eyou\TagUichannel; $__LIST__ = $tagUichannel->getUichannel("3","303", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["info"]) || (($__LIST__["info"] instanceof \think\Collection || $__LIST__["info"] instanceof \think\Paginator ) && $__LIST__["info"]->isEmpty()))): $field = $__LIST__;  $ui_typeid = !empty($field["info"]["typeid"]) ? $field["info"]["typeid"] : ""; if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  $tagType = new \think\template\taglib\eyou\TagType; $__LIST__ = $tagType->getType($typeid, "self", ""); if(is_array($__LIST__) || $__LIST__ instanceof \think\Collection || $__LIST__ instanceof \think\Paginator): if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: $field = $__LIST__;?>
       <h4 class="font-size-16 m-t-0"> <a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </h4> 
        <?php endif; else: echo htmlspecialchars_decode("");endif; ?>
       <ul class="ulstyle m-b-0"> 
        <?php  if(isset($ui_typeid) && !empty($ui_typeid)) : $typeid = $ui_typeid; else: $typeid = ""; endif; if(empty($typeid) && isset($channelartlist["id"]) && !empty($channelartlist["id"])) : $typeid = intval($channelartlist["id"]); endif;  if(isset($ui_row) && !empty($ui_row)) : $row = $ui_row; else: $row = 3; endif; $tagChannel = new \think\template\taglib\eyou\TagChannel; $_result = $tagChannel->getChannel($typeid, "son", ""); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1;$__LIST__ = is_array($_result) ? array_slice($_result,0, $row, true) : $_result->slice(0, $row, true); if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["typename"] = msubstr($field["typename"], 0, 100, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
        <li> <a href="<?php echo $field['typeurl']; ?>" title="<?php echo $field['typename']; ?>"><?php echo $field['typename']; ?></a> </li> 
        <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
       </ul> 
      <?php $ui_typeid = $ui_row = ""; endif; ?>
      </div> 
      <div class="col-lg-3 col-md-12 col-xs-12 info masonry-item font-size-20" m-id="met_contact" m-type="nocontent"> 
       <p class="font-size-26"><a href="javascript:void(0);" title="" class="eyou-edit" e-id="304" e-type="text">
         <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("304", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
          <?php echo $field['value']; else: ?>
          联系热线
        <?php endif; ?>
        </a></p> 
       <p class="eyou-edit" e-id="web_attr_2" e-type="text">
         <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("web_attr_2", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
          <?php echo $field['value']; else:  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_attr_2"); echo $_value; endif; ?>
       </p> 
       <?php if(!(empty($eyou['global']['web_attr_4']) || (($eyou['global']['web_attr_4'] instanceof \think\Collection || $eyou['global']['web_attr_4'] instanceof \think\Paginator ) && $eyou['global']['web_attr_4']->isEmpty()))): ?>
       <a class="p-r-5" id="met-weixin" data-plugin="webuiPopover" data-trigger="hover" data-animation="pop" data-placement="top" data-width="auto" data-height="auto" data-padding="0" data-content="&lt;div class='text-xs-center'&gt;&lt;img src='<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_attr_4"); echo $_value; ?>' alt='<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_name"); echo $_value; ?>' width='150' height='150' id='met-weixin-img'&gt;&lt;/div&gt;"> <i class="fa fa-weixin light-green-700"></i> </a> 
       <?php endif; if(!(empty($eyou['global']['web_attr_3']) || (($eyou['global']['web_attr_3'] instanceof \think\Collection || $eyou['global']['web_attr_3'] instanceof \think\Paginator ) && $eyou['global']['web_attr_3']->isEmpty()))): ?>
       <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_attr_3"); echo $_value; ?>&amp;site=qq&amp;menu=yes" rel="nofollow" target="_blank" class="p-r-5"> <i class="fa fa-qq"></i> </a> 
       <?php endif; if(!(empty($eyou['global']['web_attr_1']) || (($eyou['global']['web_attr_1'] instanceof \think\Collection || $eyou['global']['web_attr_1'] instanceof \think\Paginator ) && $eyou['global']['web_attr_1']->isEmpty()))): ?>
       <a href="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_attr_1"); echo $_value; ?>" rel="nofollow" target="_blank" class="p-r-5"> <i class="fa fa-weibo red-600"></i> </a> 
       <?php endif; ?>
      </div> 
     </div> 
    </div> 
   </div> 
   <div class="copy p-y-10 border-top1"> 
    <div class="container text-xs-center"> 
     <p>
      <?php  $tagFlink = new \think\template\taglib\eyou\TagFlink; $_result = $tagFlink->getFlink("all", null); if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $e = 1; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("");else: foreach($__LIST__ as $key=>$field): $field["title"] = msubstr($field["title"], 0, 15, false); $__LIST__[$key] = $_result[$key] = $field;$mod = ($e % 2 );$i= intval($key) + 1;?>
      <a href='<?php echo $field['url']; ?>'><?php echo $field['title']; ?></a>
      <?php ++$e; endforeach; endif; else: echo htmlspecialchars_decode("");endif; ?>
     </p>
    </div>
    <div class="container text-xs-center"> 
     <p class="eyou-edit" e-id="web_copyright" e-type="text">
         <?php  $tagUitext = new \think\template\taglib\eyou\TagUitext; $__LIST__ = $tagUitext->getUitext("web_copyright", "footer"); if((is_array($__LIST__)) && (!empty($__LIST__["value"]) || (($__LIST__["value"] instanceof \think\Collection || $__LIST__["value"] instanceof \think\Paginator ) && $__LIST__["value"]->isEmpty()))): $field = $__LIST__; ?>
          <?php echo $field['value']; else:  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_copyright"); echo $_value; endif; ?>
     </p>
     <p>
        <?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_recordnum"); echo $_value; ?>
     </p>
     <button type="button" class="btn btn-outline btn-default btn-squared btn-lang" id="btn-convert" m-id="lang" m-type="lang">繁體</button> 
    </div> 
   </div>  
   <?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_thirdcode_pc"); echo $_value; ?>
  </footer>
  <input type="hidden" name="met_lazyloadbg" value="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/images/loading.gif" /> 
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/jquery.min.js"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/tether.min.js"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/bootstrap.min.js"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/breakpoints.min.js"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/webui-popover.js" type="text/javascript"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/common.js" type="text/javascript"></script>
  <script src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/jquery-s2t.js" type="text/javascript"></script>
  <!-- 应用插件标签 start -->
   <?php  $tagWeapp = new \think\template\taglib\eyou\TagWeapp; $_value = $tagWeapp->getWeapp("default"); echo $_value; ?>
  <!-- 应用插件标签 end --> 
<!--footer-e--> 
<script type="text/javascript" src="<?php  $tagGlobal = new \think\template\taglib\eyou\TagGlobal; $_value = $tagGlobal->getGlobal("web_templets_pc"); echo $_value; ?>/skin/js/Swiper.js"></script>
<?php  $tagUi = new \think\template\taglib\eyou\TagUi; $_value = $tagUi->getUi(); echo $_value; ?>
</body>
</html>