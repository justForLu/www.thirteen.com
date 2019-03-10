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
class TagSearchform extends Base
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
    public function getSearchform($typeid = '', $channel = '')
    {
        $searchurl = U('home/Search/lists');

        $hidden = '';
        $ey_config = config('ey_config'); // URL模式
        if (1 == $ey_config['seo_pseudo'] && 1 == $ey_config['seo_dynamic_format']) {
            $hidden .= '<input type="hidden" name="m" value="home" />';
            $hidden .= '<input type="hidden" name="c" value="Search" />';
            $hidden .= '<input type="hidden" name="a" value="lists" />';
        }
        $hidden .= '<input type="hidden" name="typeid" id="typeid" value="'.$typeid.'" />';
        $hidden .= '<input type="hidden" name="channel" id="channel" value="'.$channel.'" />';

        $result[0] = array(
            'searchurl' => $searchurl,
            'action' => $searchurl,
            'hidden'    => $hidden,
        );
        
        return $result;
    }
}