<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

use think\Request;

/**
 * 内容页上下篇
 */
class TagPrenext extends Base
{
    public $aid = 0;
    
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->aid = I("param.aid/d", 0);
    }

    /**
     * 获取内容页上下篇
     * @author wengxianhu by 2018-4-20
     */
    public function getPrenext($get = 'pre')
    {
        $aid = $this->aid;
        if (empty($aid)) {
            echo '标签prenext报错：只能用在内容页。';
            return false;
        }

        $channelRes = model('Channeltype')->getInfoByAid($aid);
        $channel = $channelRes['channel'];
        $typeid = $channelRes['typeid'];
        $controller_name = $channelRes['ctl_name'];

        if ($get == 'next') {
            /* 下一篇 */
            $result = M('archives')->field('b.*, a.*')
                ->alias('a')
                ->join('__ARCTYPE__ b', 'b.id = a.typeid', 'LEFT')
                ->where("a.typeid = {$typeid} AND a.aid > {$aid} AND a.channel = {$channel} AND a.status = 1")
                ->order('a.aid asc')
                ->find();
            if (!empty($result)) {
                $result['arcurl'] = arcurl(MODULE_NAME.'/'.$controller_name.'/view', $result);
                /*封面图*/
                if (empty($result['litpic'])) {
                    $result['is_litpic'] = 0; // 无封面图
                } else {
                    $result['is_litpic'] = 1; // 有封面图
                }
                $result['litpic'] = get_default_pic($result['litpic']); // 默认封面图
                /*--end*/
            }
        } else {
            /* 上一篇 */
            $result = M('archives')->field('b.*, a.*')
                ->alias('a')
                ->join('__ARCTYPE__ b', 'b.id = a.typeid', 'LEFT')
                ->where("a.typeid = {$typeid} AND a.aid < {$aid} AND a.channel = {$channel} AND a.status = 1")
                ->order('a.aid desc')
                ->find();
            if (!empty($result)) {
                $result['arcurl'] = arcurl(MODULE_NAME.'/'.$controller_name.'/view', $result);
                /*封面图*/
                if (empty($result['litpic'])) {
                    $result['is_litpic'] = 0; // 无封面图
                } else {
                    $result['is_litpic'] = 1; // 有封面图
                }
                $result['litpic'] = get_default_pic($result['litpic']); // 默认封面图
                /*--end*/
            }
        }

        return $result;
    }
}