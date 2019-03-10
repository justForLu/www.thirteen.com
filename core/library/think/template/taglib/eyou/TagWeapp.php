<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 网站应用插件标签
 */
class TagWeapp extends Base
{
    public $weappidArr = array();
    
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->weappidArr = array();
    }

    /**
     * 页面上展示网站应用插件
     * @author wengxianhu by 2018-4-20
     */
    public function getWeapp($type = 'default')
    {
        /*引入全部插件内置的钩子show*/
        $map = array(
            'tag_weapp' => array('eq',1),
            'status' => array('eq',1),
        );
        $result = M('weapp')->field('code,config')->where($map)->cache(false, EYOUCMS_CACHE_TIME, 'hooks')->select();
        foreach ($result as $key => $val) {
            $config = json_decode($val['config'], true);
            if (isMobile() && !in_array($config['scene'], [0,1])) {
                continue;
            } else if (!isMobile() && !in_array($config['scene'], [0,2])) {
                continue;
            }

            $code = $val['code'];
            $class    =   get_weapp_class($code);
            if (class_exists($class)) {
                $weappClass  =   new $class;
                if (method_exists($weappClass, 'show')) {
                    hookexec("{$code}/{$code}/show");
                }
            }
        }
        /*--end*/
    }
}