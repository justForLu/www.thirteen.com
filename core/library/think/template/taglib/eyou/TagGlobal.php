<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 全局变量
 */
class TagGlobal extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 获取全局变量
     * @author wengxianhu by 2018-4-20
     */
    public function getGlobal($name = '')
    {
        if (empty($name)) {
            return '标签global报错：缺少属性 name 。';
        }

        $param = explode('|', $name);
        $name = trim($param[0], '$');
        $value = '';

        $uiset = I('param.uiset/s', 'off');
        $uiset = trim($uiset, '/');

        if (isMobile()) {
            if ($name == 'web_thirdcode_pc') {
                $name = 'web_thirdcode_wap';
            }
        } else {
            if ($name == 'web_thirdcode_wap') {
                $name = 'web_thirdcode_pc';
            }
        }
        $globalTpCache = tpCache('global');
        if ($globalTpCache) {
            $value = \think\Coding::setcr($name, $globalTpCache);

            switch ($name) {
                case 'web_basehost':
                case 'web_cmsurl':
                    {
                        if ('on' == $uiset) {
                            /*电脑版与手机版的切换*/
                            $v = I('param.v/s', 'pc');
                            $v = trim($v, '/');
                            /*--end*/
                            $value .= U('home/Index/index', array('uiset'=>'on','v'=>$v,'tmp'=>''));
                        }
                    }
                    break;
                
                case 'web_recordnum':
                    if (!empty($value)) {
                        $value = '<a href="http://www.miibeian.gov.cn/" rel="nofollow">'.$value.'</a>';
                    }
                    break;

                default:
                    # code...
                    break;
            }

            foreach ($param as $key => $val) {
                if ($key == 0) continue;
                $value = $val($value);
            }
            // $value = str_replace('"', '\"', $value);

/*            switch ($name) {
                case 'web_thirdcode_wap':
                case 'web_thirdcode_pc':
                    $value = htmlspecialchars_decode($value);
                    break;
                
                default:
                    # code...
                    break;
            }*/
            $value = htmlspecialchars_decode($value);
        }
        
        return $value;
    }
}