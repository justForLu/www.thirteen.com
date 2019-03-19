<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月15日
 *  生成sitemap文件
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\SitemapModel;

class SitemapController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new SitemapModel();
    }

    public function index()
    {
        header("Content-type:text/xml;charset=utf-8");
        $str = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<urlset>';
        $str .= $this->makeNode('', date('Y-m-d'), 1); // 根目录
        $sorts = $this->model->getSorts();
        foreach ($sorts as $value) {
            if ($value->outlink) {
                $link = $value->outlink;
            } elseif ($value->type == 1) {
                if ($value->filename) {
                    $link = url('/home/about/index/scode/' . $value->filename);
                } else {
                    $link = url('/home/about/index/scode/' . $value->scode);
                }
                $str .= $this->makeNode($link, date('Y-m-d'), 0.8);
            } else {
                if ($value->filename) {
                    $link = url('/home/list/index/scode/' . $value->filename);
                } else {
                    $link = url('/home/list/index/scode/' . $value->scode);
                }
                $str .= $this->makeNode($link, date('Y-m-d'), 0.8);
                $contents = $this->model->getList($value->scode);
                foreach ($contents as $value2) {
                    if ($value2->outlink) { // 外链
                        $link = $value2->outlink;
                    } elseif ($value2->filename) { // 自定义名称
                        $link = url('/home/content/index/id/' . $value2->filename);
                    } else {
                        $link = url('/home/content/index/id/' . $value2->id);
                    }
                    $str .= $this->makeNode($link, date('Y-m-d'), 0.6);
                }
            }
        }
        echo $str . "\n</urlset>";
    }

    /**
     * 生成结点信息
     *
     * @param $link
     * @param $date
     * @param float $priority
     * @return string
     */
    private function makeNode($link, $date, $priority = 0.6)
    {
        $node = '<url><loc>' . get_http_url() . $link . '</loc><lastmod>' . $date . '</lastmod><changefreq>daily</changefreq><priority>' . $priority . '</priority></url>';
        return $node;
    }
}