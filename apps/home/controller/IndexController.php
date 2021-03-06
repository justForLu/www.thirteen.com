<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年2月14日
 *  首页控制器
 */
namespace app\home\controller;

use core\basic\Controller;

class IndexController extends Controller
{

    protected $parser;

    public function __construct()
    {
        $this->parser = new ParserController();
    }

    /**
     * 首页
     */
    public function index()
    {
        $content = parent::parser('index.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = $this->parser->parserPositionLabel($content, - 1, '首页', SITE_DIR . '/'); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, 0, '', SITE_DIR . '/'); // 解析分类标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        $this->cache($content, true);
    }

    /**
     * 空拦截
     */
    public function _empty()
    {
        error('您访问的地址有误，请核对后重试！');
    }
}