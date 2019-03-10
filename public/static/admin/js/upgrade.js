// 系统升级 js 文件


$(document).ready(function(){
    $("#a_upgrade").click(function(){
        btn_upgrade(this, 0);  
    });
});

function btn_upgrade(obj, type)
{
    var v = $("#textarea_filelist").val();    
    var intro = $("#textarea_intro").val();
    var notice = $("#textarea_notice").val();
    intro += '<style type="text/css">.layui-layer-content{height:270px!important}</style>';
    // v = v.replace(/\n/g,"<br/>");
    v = notice + intro + '<br/>' + v;
    var version = $(obj).data('version');
    var max_version = $(obj).data('max_version');
    var title = '检测系统最新版本：'+version;

    if (0 == type) {
        var btn = ['升级','忽略'];
    } else if (1 == type) {
        var btn = ['升级','忽略','不再提醒'];
    }

    /*显示顶部导航更新提示*/
    $("#textarea_filelist", window.parent.document).val($("#textarea_filelist").val());    
    $("#textarea_intro", window.parent.document).val($("#textarea_intro").val());
    $("#textarea_notice", window.parent.document).val($("#textarea_notice").val());
    $('#a_upgrade', window.parent.document).attr('data-version',version)
        .attr('data-max_version',max_version)
        .show();
    /*--end*/

    //询问框
    layer.confirm(v, {
            title: title
            ,area: ['580px','400px']
            ,btn: btn //按钮
            ,btn3: function(index){
                var url = $(obj).data('tips_url');
                $.getJSON(url, {show_popup_upgrade:-1}, function(){});
                layer.msg('【核心设置】里可以开启该提醒', {
                    btnAlign: 'c',
                    time: 20000, //20s后自动关闭
                    btn: ['知道了']
                });
                return false;
            }

        }, function(){
            layer_loading('升级中');
            upgrade(obj); // 请求后台
        }, function(){  
            layer.msg('不升级可能有安全隐患', {
                btnAlign: 'c',
                time: 20000, //20s后自动关闭
                btn: ['明白了']
            });
            return false;

        }
    );   
}

function upgrade(obj){
    var version = $(obj).data('version');
    var max_version = $(obj).data('max_version');
    $.ajax({
        type : "GET",
        url  :  $(obj).data('upgrade_url'),
        timeout : 360000, //超时时间设置，单位毫秒 设置了 1小时
        data : {},
        error: function(request) {
            layer.closeAll();
            layer.alert("网络请求失败", {icon: 2}, function(){
                top.location.reload();
            });
        },
        success: function(v) {
            layer.closeAll();
            if(v=='1'){
                if (version < max_version) { // 当前升级之后的版本还不是官方最新版本，将继续连续更新
                    var title = '已升级版本：'+version+'，官方最新版本：'+max_version+'。';
                    var btn = ['开始检测'];
                } else { // 升级版本是官方最新版本，将引导到备份新数据
                    var title = '已升级最新版本，请备份新数据。<font color="red"><br/>提示：之前备份不兼容新版本。</font>';
                    var btn = ['前往备份'];
                    $('#a_upgrade', window.parent.document).hide(); // 隐藏顶部的更新提示
                }
                var full = layer.alert(title, {
                        title: '重要提示',
                        btn: btn //按钮
                    }, function(){
                        if (version < max_version) { // 当前升级之后的版本还不是官方最新版本，将继续连续更新
                            top.location.reload();
                        } else { // 升级版本是官方最新版本，将引导到备份新数据
                            layer.close(full);
                            var url = eyou_basefile + "?m="+module_name+"&c=Tools&a=index";
                            var iframe = $(obj).data('iframe');
                            if ('parent' == iframe) {
                                workspace.window.location.href = url;
                            } else {
                                window.location.href = url;
                            }
                        }
                    }
                );
            }
            else{
                layer.alert(v, {icon: 2}, function(){
                    top.location.reload();
                });
            }
        }
    });                 
}

function layer_loading(msg){
    var loading = layer.msg(
    msg+'...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请勿刷新页面', 
    {
        icon: 1,
        time: 3600000, //1小时后后自动关闭
        shade: [0.2] //0.1透明度的白色背景
    });
    //loading层
    var index = layer.load(3, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });

    return loading;
}

/*
$('#').click(funcion(){

});


 
*/