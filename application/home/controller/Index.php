<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */

namespace app\home\controller;

class Index extends Base
{
    public function _initialize() {
        parent::_initialize();
    }

    public function index()
    {
        if (config('is_https')) {
            $filename = 'indexs.html';
        } else {
            $filename = 'index.html';
        }

        if (file_exists($filename)) {
            @unlink($filename);
        }

        //自动生成HTML版
        if(isset($_GET['clear']) || !file_exists($filename))
        {
            /*获取当前页面URL*/
            $result['pageurl'] = request()->domain();
            /*--end*/
            $eyou = array(
                'field' => $result,
            );
            $this->eyou = array_merge($this->eyou, $eyou);
            $this->assign('eyou', $this->eyou);
            $html = $this->fetch(':index');
            // @file_put_contents($filename, $html);
            return $html;
        }
        else
        {
            // header('HTTP/1.1 301 Moved Permanently');
            // header('Location:'.$filename);
        }
    }
}