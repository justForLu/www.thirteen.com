<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;

class Sitemap extends Base
{
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 生成相应的搜索引擎sitemap
     */
    public function index($ver = 'xml')
    {
        if (empty($ver)) {
            sitemap_all();
        } else {
            $fun_name = 'sitemap_'.$ver;
            $fun_name();
        }
    }
}
