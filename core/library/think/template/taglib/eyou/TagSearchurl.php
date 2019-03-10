<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 搜索表单
 */
class TagSearchurl extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 获取搜索表单
     * @author wengxianhu by 2018-4-20
     */
    public function getSearchurl()
    {
		$url = url("home/Search/lists");
        $ey_config = config('ey_config'); // URL模式
        if (1 == $ey_config['seo_pseudo'] && 1 == $ey_config['seo_dynamic_format']) {
        	$result = '';
            $result .= $url.'"><input type="hidden" name="m" value="home" />';
            $result .= '<input type="hidden" name="c" value="Search" />';
            $result .= '<input type="hidden" name="a" value="lists';
        } else {
			$result = $url;
		}
		
        return $result;
    }
}