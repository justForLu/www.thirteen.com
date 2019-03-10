<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 单个广告信息
 */
class TagAd extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 获取单个广告信息
     * @author wengxianhu by 2018-4-20
     */
    public function getAd($aid = '')
    {
        if (empty($aid)) {
            echo '标签ad报错：缺少属性 aid 值。';
            return false;
        }

        $result = M("ad")->cache(true,EYOUCMS_CACHE_TIME,"ad")->find($aid);
        if (empty($result)) {
            echo '标签ad报错：该广告ID('.$aid.')不存在。';
            return false;
        }
        
        $result['litpic'] = get_default_pic($result['litpic']); // 默认无图封面
        $result['intro'] = htmlspecialchars_decode($result['intro']); // 解码内容

        return $result;
    }
}