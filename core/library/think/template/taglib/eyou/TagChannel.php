<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

use think\Request;

/**
 * 栏目列表
 */
class TagChannel extends Base
{
    public $tid = '';
    public $currentstyle = '';
    
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->tid = I("param.tid/s", ''); // 应用于栏目列表
        /*应用于文档列表*/
        $aid = I('param.aid/d', 0);
        if ($aid > 0) {
            $cacheKey = 'tagChannel_'.strtolower(MODULE_NAME.'_'.CONTROLLER_NAME.'_'.ACTION_NAME);
            $cacheKey .= "_{$aid}";
            $this->tid = cache($cacheKey);
            if ($this->tid == false) {
                $this->tid = M('archives')->where('aid', $aid)->getField('typeid');
                cache($cacheKey, $this->tid);
            }
        }
        /*--end*/
        /*tid为目录名称的情况下*/
        $this->tid = $this->getTrueTypeid($this->tid);
        /*--end*/
    }

    /**
     * 获取指定级别的栏目列表
     * @param string type son表示下一级栏目,self表示同级栏目,top顶级栏目
     * @param boolean $self 包括自己本身
     * @author wengxianhu by 2018-4-26
     */
    public function getChannel($typeid = '', $type = 'top', $currentstyle = '')
    {
        $this->currentstyle = $currentstyle;
        $typeid  = !empty($typeid) ? $typeid : $this->tid;

        if (empty($typeid)) {
            /*应用于没有指定tid的列表，默认获取该控制器下的第一级栏目ID*/
            // http://demo.eyoucms.com/index.php/home/Article/lists.html
            $controller_name = request()->controller();
            $channeltype_info = model('Channeltype')->getInfoByWhere(array('ctl_name'=>$controller_name), 'id');
            $channeltype = $channeltype_info['id'];
            $map = array(
                'channeltype'   => $channeltype,
                'parent_id' => 0,
                'is_hidden' => 0,
                'status'    => 1,
            );
            $typeid = M('arctype')->where($map)->order('sort_order asc')->limit(1)->getField('id');
            /*--end*/
        }

        $result = $this->getSwitchType($typeid, $type);

        return $result;
    }

    /**
     * 获取指定级别的栏目列表
     * @param string type son表示下一级栏目,self表示同级栏目,top顶级栏目
     * @param boolean $self 包括自己本身
     * @author wengxianhu by 2018-4-26
     */
    public function getSwitchType($typeid = '', $type = 'top')
    {
        $result = array();
        switch ($type) {
            case 'son': // 下级栏目
                $result = $this->getSon($typeid, false);
                break;

            case 'self': // 同级栏目
                $result = $this->getSelf($typeid);
                break;

            case 'top': // 顶级栏目
                $result = $this->getTop();
                break;

            case 'sonself': // 下级、同级栏目
                $result = $this->getSon($typeid, true);
                break;

            case 'first': // 第一级栏目
                $result = $this->getFirst($typeid);
                break;
        }

        /*处理自定义表字段的值*/
        if (!empty($result)) {
            /*获取自定义表字段信息*/
            $map = array(
                'channel_id'    => config('global.arctype_channel_id'),
            );
            $fieldInfo = model('Channelfield')->getListByWhere($map, '*', 'name');
            /*--end*/
            $fieldLogic = new \app\home\logic\FieldLogic;
            foreach ($result as $key => $val) {
                if (!empty($val)) {
                    $val = $fieldLogic->handleAddonFieldList($val, $fieldInfo);
                    $result[$key] = $val;
                }
            }
        }
        /*--end*/

        return $result;
    }

    /**
     * 获取下一级栏目
     * @param string $self true表示没有子栏目时，获取同级栏目
     * @author wengxianhu by 2017-7-26
     */
    public function getSon($typeid, $self = false)
    {
        $result = array();
        if (empty($typeid)) {
            return $result;
        }

        $arctype_max_level = intval(config('global.arctype_max_level')); // 栏目最多级别
        /*获取所有显示且有效的栏目列表*/
        $map = array(
            'c.is_hidden'   => 0,
            'c.status'  => 1,
        );
        $fields = "c.*, c.id as typeid, count(s.id) as has_children, '' as children";
        $res = db('arctype')
            ->field($fields)
            ->alias('c')
            ->join('__ARCTYPE__ s','s.parent_id = c.id','LEFT')
            ->where($map)
            ->group('c.id')
            ->order('c.parent_id asc, c.sort_order asc, c.id')
            ->cache(true,EYOUCMS_CACHE_TIME,"arctype")
            ->select();
        /*--end*/
        if ($res) {
            $ctl_name_list = model('Channeltype')->getAll('id,ctl_name', array(), 'id');
            foreach ($res as $key => $val) {
                /*获取指定路由模式下的URL*/
                if ($val['is_part'] == 1) {
                    $val['typeurl'] = $val['typelink'];
                } else {
                    $ctl_name = $ctl_name_list[$val['current_channel']]['ctl_name'];
                    $val['typeurl'] = typeurl(MODULE_NAME.'/'.$ctl_name."/lists", $val);
                }
                /*--end*/

                /*标记栏目被选中效果*/
                if ($val['id'] == $typeid || $val['id'] == $this->tid) {
                    $val['currentstyle'] = $this->currentstyle;
                } else {
                    $val['currentstyle'] = '';
                }
                /*--end*/

                $res[$key] = $val;
            }
        }

        /*栏目层级归类成阶梯式*/
        $arr = group_same_key($res, 'parent_id');
        for ($i=0; $i < $arctype_max_level; $i++) {
            foreach ($arr as $key => $val) {
                foreach ($arr[$key] as $key2 => $val2) {
                    if (!isset($arr[$val2['id']])) continue;
                    $val2['children'] = $arr[$val2['id']];
                    $arr[$key][$key2] = $val2;
                }
            }
        }
        /*--end*/

        /*取得指定栏目ID对应的阶梯式所有子孙等栏目*/
        $result = array();
        $typeidArr = explode(',', $typeid);
        foreach ($typeidArr as $key => $val) {
            if (!isset($arr[$val])) continue;
            if (is_array($arr[$val])) {
                foreach ($arr[$val] as $key2 => $val2) {
                    array_push($result, $val2);
                }
            } else {
                array_push($result, $arr[$val]);
            }
        }
        /*--end*/

        /*没有子栏目时，获取同级栏目*/
        if (empty($result) && $self == true) {
            $result = $this->getSelf($typeid);
        }
        /*--end*/

        return $result;
    }

    /**
     * 获取当前栏目的第一级栏目下的子栏目
     * @author wengxianhu by 2017-7-26
     */
    public function getFirst($typeid)
    {
        $result = array();
        if (empty($typeid)) {
            return $result;
        }

        $row = model('Arctype')->getAllPid($typeid); // 当前栏目往上一级级父栏目
        if (!empty($row)) {
            reset($row); // 重置排序
            $firstResult = current($row); // 顶级栏目下的第一级父栏目
            $typeid = isset($firstResult['id']) ? $firstResult['id'] : '';
            $sonRow = $this->getSon($typeid, false); // 获取第一级栏目下的子孙栏目，为空时不获得同级栏目
            $result = $sonRow;
        }

        return $result;
    }

    /**
     * 获取同级栏目
     * @author wengxianhu by 2017-7-26
     */
    public function getSelf($typeid)
    {
        $result = array();
        if (empty($typeid)) {
            return $result;
        }

        /*获取指定栏目ID的上一级栏目ID列表*/
        $map = array(
            'id'   => array('in', $typeid),
            'is_hidden'   => 0,
            'status'  => 1,
        );
        $res = M('arctype')->field('parent_id')->where($map)->group('parent_id')->select();
        /*--end*/

        /*获取上一级栏目ID对应下的子孙栏目*/
        if ($res) {
            $typeidArr = get_arr_column($res, 'parent_id');
            $typeid = implode(',', $typeidArr);
            if ($typeid == 0) {
                $result = $this->getTop();
            } else {
                $result = $this->getSon($typeid, false);
            }
        }
        /*--end*/

        return $result;
    }

    /**
     * 获取顶级栏目
     * @author wengxianhu by 2017-7-26
     */
    public function getTop()
    {
        $result = array();

        /*获取所有栏目*/
        $arctypeLogic = new \app\common\logic\ArctypeLogic();
        $arctype_max_level = intval(config('global.arctype_max_level'));
        $map = array(
            'is_hidden'   => 0,
            'status'  => 1,
        );
        $res = $arctypeLogic->arctype_list(0, 0, false, $arctype_max_level, $map);
        /*--end*/

        if (count($res) > 0) {
            $ctl_name_list = model('Channeltype')->getAll('id,ctl_name', array(), 'id');
            
            foreach ($res as $key => $val) {
                /*获取指定路由模式下的URL*/
                if ($val['is_part'] == 1) {
                    $val['typeurl'] = $val['typelink'];
                } else {
                    $ctl_name = $ctl_name_list[$val['current_channel']]['ctl_name'];
                    $val['typeurl'] = typeurl(MODULE_NAME.'/'.$ctl_name."/lists", $val);
                }
                /*--end*/

                /*标记栏目被选中效果*/
                if ($val['id'] == $this->getTopTypeid($this->tid)) {
                    $val['currentstyle'] = $this->currentstyle;
                } else {
                    $val['currentstyle'] = '';
                }
                /*--end*/

                $res[$key] = $val;
            }

            /*栏目层级归类成阶梯式*/
            $arr = group_same_key($res, 'parent_id');
            for ($i=0; $i < $arctype_max_level; $i++) { 
                foreach ($arr as $key => $val) {
                    foreach ($arr[$key] as $key2 => $val2) {
                        if (!isset($arr[$val2['id']])) continue;
                        $val2['children'] = $arr[$val2['id']];
                        $arr[$key][$key2] = $val2;
                    }
                }
            }
            /*--end*/

            reset($arr);  // 重置数组
            /*获取第一个数组*/
            $firstResult = current($arr);
            $result = $firstResult;
            /*--end*/
        }

        return $result;
    }

    /**
     * 获取最顶级父栏目ID
     */
    public function getTopTypeid($typeid)
    {
        $topTypeId = 0;
        if ($typeid > 0) {
            $result = model('Arctype')->getAllPid($typeid); // 当前栏目往上一级级父栏目
            reset($result); // 重置数组
            /*获取最顶级父栏目ID*/
            $firstVal = current($result);
            $topTypeId = $firstVal['id'];
            /*--end*/
        }

        return intval($topTypeId);
    }
}