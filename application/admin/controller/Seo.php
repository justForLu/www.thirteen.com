<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;
use think\Db;
use think\Cache;
use app\common\logic\ArctypeLogic;

class Seo extends Base
{
    /*
     * 配置入口
     */
    public function index()
    {
        /*配置列表*/
        $group_list = [
            'seo' => 'SEO基础',
            'rewrite'     => '伪静态',
            // 'html'       => '静态页面',
            'sitemap'      => 'Sitemp',
        ];      
        $this->assign('group_list',$group_list);
        $inc_type =  I('get.inc_type','seo');
        $this->assign('inc_type',$inc_type);
        $config = tpCache($inc_type);
        if($inc_type == 'seo'){
            $seo_pseudo_list = get_seo_pseudo_list();
            $this->assign('seo_pseudo_list', $seo_pseudo_list);
        } elseif ($inc_type == 'html') {
            // 栏目列表
            $arctypeLogic = new ArctypeLogic();
            $select_html = $arctypeLogic->arctype_list(0, 0, true, config('global.arctype_max_level'));
            $this->assign('select_html',$select_html);
        }
        $this->assign('config',$config);//当前配置项
        return $this->fetch($inc_type);
    }
    
    /*
     * 新增修改配置
     */
    public function handle()
    {
        $param = I('post.');
        $inc_type = $param['inc_type'];
        if ($inc_type == 'seo') {
            /*检测是否开启pathinfo模式*/
            try {
                if (3 == $param['seo_pseudo'] || (1 == $param['seo_pseudo'] && 2 == $param['seo_dynamic_format'])) {
                    $fix_pathinfo = ini_get('cgi.fix_pathinfo');
                    if (stristr($_SERVER['HTTP_HOST'], '.mylightsite.com')) {
                        $this->error('腾讯云空间不支持伪静态！');
                    } else if ('' != $fix_pathinfo && 0 === $fix_pathinfo) {
                        $this->error('空间不支持伪静态，请开启pathinfo，或者在php.ini里修改cgi.fix_pathinfo=1');
                    }
                }
            } catch (Exception $e) {}
            /*--end*/
            // $param['seo_arcdir'] = rtrim($param['seo_arcdir'], '/');
        } elseif($inc_type == 'sitemap'){
            $param['sitemap_not1'] = isset($param['sitemap_not1']) ? $param['sitemap_not1'] : 0;
            $param['sitemap_not2'] = isset($param['sitemap_not2']) ? $param['sitemap_not2'] : 0;
            $param['sitemap_xml'] = isset($param['sitemap_xml']) ? $param['sitemap_xml'] : 0;
            $param['sitemap_txt'] = isset($param['sitemap_txt']) ? $param['sitemap_txt'] : 0;
            /* 生成sitemap */
            sitemap_all();
        }
        unset($param['inc_type']);
        tpCache($inc_type,$param);
        
        if ($inc_type == 'seo') {
            // 清空缓存
            delFile(rtrim(HTML_ROOT, '/'));
            \think\Cache::clear();
        }
        $this->success('操作成功', U('Seo/index',array('inc_type'=>$inc_type)));
    }
    
    /*
     * 生成静态页面
     */
    public function htmlHandle()
    {
        $param = I('param.');
        $inc_type = $param['inc_type'];
        $typeid = isset($param['typeid']) ? $param['typeid'] : 0;
        $html_startid = isset($param['html_startid']) ? $param['html_startid'] : 0;
        $html_endid = isset($param['html_endid']) ? $param['html_endid'] : 0;

        $this->bindArchivesHtml($typeid, $html_startid, $html_endid);

        $this->success('操作成功', U('Seo/index',array('inc_type'=>$inc_type)));
    }

    /*
     * 生成静态页面
     */
    public function bindHtml()
    {
        $param = I('param.');
        // $inc_type = $param['inc_type'];
        $typeid = isset($param['typeid']) ? $param['typeid'] : 0;
        $html_startid = isset($param['html_startid']) ? $param['html_startid'] : 0;
        $html_endid = isset($param['html_endid']) ? $param['html_endid'] : 0;
        $updatetype = isset($param['updatetype']) ? $param['updatetype'] : 'index';

        if ($updatetype == 'index') {
            $res = $this->bindIndexHtml();
        } elseif ($updatetype == 'archives') {
            $res = $this->bindArchivesHtml($typeid, $html_startid, $html_endid);
        } elseif ($updatetype == 'arctype') {
            $res = $this->bindArctypeHtml($typeid);
        } elseif ($updatetype == 'all') {
            $res1 = $this->bindArctypeHtml($typeid);

            $res = $this->bindArchivesHtml($typeid, $html_startid, $html_endid);
        }

        respose(array(
            'urls' => $res['urls'],
            'nowurls' => $res['nowurls'],
            'total'=> count($res['urls']),
        ));
    }

    /**
     * 更新首页html
     */
    public function bindIndexHtml()
    {
        if (config('is_https')) {
            $filename = 'indexs.html';
        } else {
            $filename = 'index.html';
        }
        $filename = ROOT_PATH.$filename;
        if (file_exists($filename)) {
            @unlink($filename);
        }

        return array(
            'urls' => array(SITE_URL),
            'nowurls' => array(SITE_URL),
        );
    }

    /**
     * 更新文档html
     */
    public function bindArchivesHtml($typeid = '', $startid = '', $endid = '')
    {
        $channelList = model('Channeltype')->getAll('*', array(), 'id');
        $arctypeList = model('Arctype')->getAll('*', array(), 'id');

        if ($typeid > 0) {
            $result = model('Arctype')->getHasChildren($typeid);
            $map['typeid'] = array('in', get_arr_column($result, 'id'));
        }
        if ($startid > 0) {
            $map['aid'] = array('egt', $startid);
        }
        if ($endid > 0) {
            $map['aid'] = array('elt', $endid);
        }
        // if (intval($pagesize) == 0) {
            $pagesize = '';
        // }
        $map['is_jump'] = array('eq', 0);
        $map['channel'] = array('neq', 6);
        $map['status'] = array('eq', 1);

        $url_arr = array();
        $nowurl_arr = array();
        $result = M('archives')->where($map)->limit($pagesize)->select();
        foreach ($result as $key => $val) {
            $val = array_merge($arctypeList[$val['typeid']], $val);

            $ctl_name = $channelList[$val['channel']]['ctl_name'];
            $nowarcurl = arcurl('home/'.$ctl_name.'/view', $val, true, SITE_URL, 2);
            $arcurl = arcurl('home/'.$ctl_name.'/view', $val, true, SITE_URL, 1);

            array_push($url_arr, $arcurl);
            array_push($nowurl_arr, $nowarcurl);
        }

        return array(
            'urls' => $url_arr,
            'nowurls' => $nowurl_arr,
        );
    }

    /**
     * 更新栏目html
     */
    public function bindArctypeHtml($typeid = '')
    {
        $channelList = model('Channeltype')->getAll('*', array(), 'id');

        if (empty($typeid)) {
            $typeid = 0;
        }

        $url_arr = array();
        $nowurl_arr = array();
        $result = model('Arctype')->getHasChildren($typeid);

        $module_name_tmp = 'home';
        $action_name_tmp = 'lists';
        foreach ($result as $key => $val) {
            $ctl_name = $channelList[$val['current_channel']]['ctl_name'];
            $cacheKey = strtolower("taglist_lastPage_home{$ctl_name}lists".$val['id']);
            $lastPage = cache($cacheKey); // 用于静态页面的分页生成
            for ($i=1; $i <= $lastPage; $i++) { 
                $nowtypeurl = typeurl('home/'.$ctl_name.'/lists', $val, true, SITE_URL, 2);
                if ($i == 1) {
                    $nowtypeurl .= 'index.html';
                } else {
                    $nowtypeurl .= 'list_'.$val['id'].'_'.$i.'.html';
                }
                $typeurl = typeurl('home/'.$ctl_name.'/lists', $val, true, SITE_URL, 1).'&page='.$i;

                array_push($url_arr, $typeurl);
                array_push($nowurl_arr, $nowtypeurl); 
            }
        }

        return array(
            'urls' => $url_arr,
            'nowurls' => $nowurl_arr,
        );
    }
}