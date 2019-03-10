<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;


/**
 * 资源文件加载
 */
class TagLoad extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 资源文件加载
     * @author wengxianhu by 2018-4-20
     */
    public function getLoad($file = '', $ver = 'on')
    {
        if (empty($file)) {
            return '标签load报错：缺少属性 href 或 file 。';
        }

        $parseStr = '';

        // 文件方式导入
        $array = explode(',', $file);
        foreach ($array as $val) {
            $type = strtolower(substr(strrchr($val, '.'), 1));
            $version = getCmsVersion();
            switch ($type) {
                case 'js':
                    if ($ver == 'on') {
                        $parseStr .= static_version($val);
                    } else {
                        $parseStr .= '<script type="text/javascript" src="' . $val . '?v='.$version.'"></script>';
                    }
                    break;
                case 'css':
                    if ($ver == 'on') {
                        $parseStr .= static_version($val);
                    } else {
                        $parseStr .= '<link rel="stylesheet" type="text/css" href="' . $val . '?v='.$version.'" />';
                    }
                    break;
                case 'php':
                    $parseStr .= '<?php include "' . $val . '"; ?>';
                    break;
            }
        }

        return $parseStr;
    }
}