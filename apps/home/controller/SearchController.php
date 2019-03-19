<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月7日
 *  
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\ParserModel;

class SearchController extends Controller
{

    protected $parser;

    protected $model;

    public function __construct()
    {
        $this->parser = new ParserController();
        $this->model = new ParserModel();
    }

    public function index()
    {
        $content = parent::parser('search.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = $this->parser->parserPositionLabel($content, 0, '搜索', url('/home/Search/index')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 1, '搜索结果', url('/home/Search/index')); // 解析分类标签
        $content = $this->parser->parserSearchLabel($content); // 搜索结果标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        $this->cache($content, true);
    }
}