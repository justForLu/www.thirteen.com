<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\api\controller;

class Count extends Base
{
    /*
     * 初始化操作
     */
    
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 内容页浏览量的自增
     */
    public function view()
    {
        $aid = I('aid/d', 0);
        $click = 0;
        if (empty($aid)) {
            echo($click);
            exit;
        }

        if ($aid > 0) {
            M('archives')->where(array('aid'=>$aid))->setInc('click'); 
            $click = M('archives')->where(array('aid'=>$aid))->getField('click');
        }

        echo($click);
        exit;
    }
}