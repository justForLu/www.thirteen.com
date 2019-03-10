<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\api\controller;

class Rewrite extends Base
{
    /*
     * 初始化操作
     */
    
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 检测服务器是否支持URL重写隐藏应用的入口文件index.php
     */
    public function testing()
    {
        ob_clean();
        exit('ok');
    }
}