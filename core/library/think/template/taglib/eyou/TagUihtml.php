<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 富文本编辑
 */
class TagUihtml extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 富文本编辑
     * @author wengxianhu by 2018-4-20
     */
    public function getUihtml($e_id = '', $e_page = '')
    {
        if (empty($e_id) || empty($e_page)) {
            echo '标签uihtml报错：缺少属性 e-id | e-page 。';
            return false;
        }

        $result = false;
        $inckey = "html_{$e_id}";
        $inc = get_ui_inc_params($e_page);

        $info = false;
        if ($inc && !empty($inc[$inckey])) {
            $data = json_decode($inc[$inckey], true);
            $info = $data['info'];
        }

        $value = '';
        if (is_array($info) && !empty($info)) {
            $value .= isset($info['value']) ? $info['value'] : '';
            $value = preg_replace('#<p([^>]*)>(\s*)(<b class="ui_icon"><\/b>|<strong class="ui_icon"><\/strong>)<(\s*)\/p>#', '', $value);
            $value = htmlspecialchars_decode($value);
        }

        $result = array(
            'value'  => $value,
        );

        return $result;
    }
}