<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 在内容页模板追加显示浏览量
 */
class TagArcclick extends Base
{
    public $aid = 0;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->aid = I('param.aid/d', 0);
    }

    /**
     * 在内容页模板追加显示浏览量
     * @author wengxianhu by 2018-4-20
     */
    public function getArcclick($aid = '', $value = '')
    {
        $aid = !empty($aid) ? $aid : $this->aid;

        if (empty($aid)) {
            return '标签arcclick报错：缺少属性 aid 值。';
        }

        if (empty($value)) {
            $value = M('archives')->where('aid', $aid)->getField('click');
        }

        $web_cmspath = tpCache('global.web_cmspath');

        $str = <<<EOF
<i id="eyou_arcclick" class="eyou_arcclick" style="font-style:normal">{$value}</i> 
<script type="text/javascript">
    //步骤一:创建异步对象
    var ajax = new XMLHttpRequest();
    //步骤二:设置请求的url参数,参数一是请求的类型,参数二是请求的url,可以带参数,动态的传递参数starName到服务端
    ajax.open("get", "/index.php?m=api&c=Count&a=view&aid={$aid}");
    //步骤三:发送请求
    ajax.send();
    //步骤四:注册事件 onreadystatechange 状态改变就会调用
    ajax.onreadystatechange = function (res) {
       if (ajax.readyState==4 && ajax.status==200) {
        //步骤五 如果能够进到这个判断 说明 数据 完美的回来了,并且请求的页面是存在的
    　　　　document.getElementById("eyou_arcclick").innerHTML = ajax.responseText;
      　}
    }
</script>
EOF;
        return $str;
    }
}