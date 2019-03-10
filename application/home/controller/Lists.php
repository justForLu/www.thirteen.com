<?php
/**
 * 拾叁网络科技
 *
 * Date: 2019-02-13
 */

namespace app\home\controller;

class Lists extends Base
{
    // 模型标识
    public $nid = '';
    // 模型ID
    public $channel = '';

    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 栏目列表
     */
    public function index($tid = '')
    {
        /*获取当前栏目ID以及模型ID*/
        $dirname = '';
        if (empty($tid)) {
            abort(404,'页面不存在');
        } else {
            if (strval(intval($tid)) != strval($tid)) {
                $map = array('a.dirname'=>$tid);
            } else {
                $map = array('a.id'=>$tid);
            }
            $row = M('arctype')->field('a.id, a.current_channel, b.nid')
                ->alias('a')
                ->join('__CHANNELTYPE__ b', 'a.current_channel = b.id', 'LEFT')
                ->where($map)
                ->find();
            $tid = $row['id'];
            $this->nid = $row['nid'];
            $this->channel = intval($row['current_channel']);
        }
        /*--end*/

        $result = $this->logic($tid); // 模型对应逻辑

        $eyou = array(
            'field' => $result,
        );
        $this->eyou = array_merge($this->eyou, $eyou);
        $this->assign('eyou', $this->eyou);

        /*模板文件*/
        $templist = !empty($result['templist']) ? $result['templist'] : 'lists_'.$this->nid.'.'.$this->view_suffix;
        /*--end*/

        $fetch_tpl = 'template/'.$this->theme_style.'/'.$templist;
        return $this->fetch($fetch_tpl);
    }

    /**
     * 模型对应逻辑
     * @param intval $tid 栏目ID
     * @return array
     */
    private function logic($tid = '')
    {
        $result = array();

        if (empty($tid)) {
            return $result;
        }

        switch ($this->channel) {
            case '6': // 单页模型
            {
                $arctype_info = model('Arctype')->getInfo($tid);
                if ($arctype_info) {
                    // 读取当前栏目的内容，否则读取每一级第一个子栏目的内容，直到有内容或者最后一级栏目为止。
                    $result = $this->readContentFirst($tid);
                    // 阅读权限
                    if ($result['arcrank'] == -1) {
                        $this->success('待审核稿件，你没有权限阅读！');
                        exit;
                    }
                    // 外部链接跳转
                    if ($result['is_part'] == 1) {
                        header('Location: '.$result['typelink']);
                        exit;
                    }
                    /*自定义字段的数据格式处理*/
                    $result = $this->fieldLogic->getChannelFieldList($result, $this->channel);
                    /*--end*/
                    $result = array_merge($arctype_info, $result);
                }
                break;
            }

            default:
            {
                $result = model('Arctype')->getInfo($tid);
                break;
            }
        }

        if (!empty($result)) {
            /*自定义字段的数据格式处理*/
            $result = $this->fieldLogic->getTableFieldList($result, config('global.arctype_channel_id'));
            /*--end*/
        }

        /*是否有子栏目，用于标记【全部】选中状态*/
        $result['has_children'] = model('Arctype')->hasChildren($tid);
        /*--end*/

        // seo
        $result['seo_title'] = set_typeseotitle($result['typename'], $result['seo_title']);

        /*获取当前页面URL*/
        $result['pageurl'] = request()->domain().request()->url();
        /*--end*/

        return $result;
    }

    /**
     * 读取指定栏目ID下有内容的栏目信息，只读取每一级的第一个栏目
     * @param intval $typeid 栏目ID
     * @return array
     */
    private function readContentFirst($typeid)
    {
        $result = false;
        while (true)
        {
            $result = model('Single')->getInfoByTypeid($typeid);
            if (empty($result['content'])) {
                $map = array(
                    'parent_id' => $result['typeid'],
                    'current_channel' => 6,
                    'is_hidden' => 0,
                    'status'    => 1,
                );
                $row = M('arctype')->where($map)->field('*')->order('sort_order asc')->find(); // 查找下一级的单页模型栏目
                if (empty($row)) { // 不存在并返回当前栏目信息
                    break;
                } elseif (6 == $row['current_channel']) { // 存在且是单页模型，则进行继续往下查找，直到有内容为止
                    $typeid = $row['id'];
                }
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * 读取指定栏目ID下有内容的栏目信息，只读取每一级的第一个栏目
     * @param intval $typeid 栏目ID
     * @return array
     */
/*    private function readContentFirst_old($typeid)
    {
        $result = false;
        while (true)
        {
            $result = model('Single')->getInfoByTypeid($typeid);
            if (empty($result['content'])) {
                $map = array(
                    'parent_id' => $result['typeid'],
                    'is_hidden' => 0,
                    'status'    => 1,
                );
                $row = M('arctype')->where($map)->field('*')->order('sort_order asc')->find(); // 查找下一级的第一个栏目
                if (empty($row)) { // 不存在并返回当前栏目信息
                    break;
                } elseif (6 != $row['current_channel']) { // 存在且不是单页模型，并进行跳转
                    $typeurl = model('Arctype')->getTypeUrl($row);
                    header('Location: '.$typeurl);
                    exit;
                } elseif (6 == $row['current_channel']) { // 存在且是单页模型，则进行继续往下查找，直到有内容为止
                    $typeid = $row['id'];
                }
            } else {
                break;
            }
        }

        return $result;
    }*/

    /**
     * 留言提交 
     */
    public function gbook_submit()
    {
        $post = I('post.');
        $typeid = I('post.typeid/d');
        $ip = clientIP();

        if (!empty($typeid)) {
            $map = array(
                'ip'    => $ip,
                'typeid'    => $typeid,
                'add_time'  => array('gt', getTime() - 60),
            );
            $count = M('guestbook')->where($map)->count('aid');
            if ($count > 0) {
                $this->error('同一个IP在60秒之内不能重复提交！');
                exit;
            }

            $newData = array(
                'typeid'    => $typeid,
                'channel'   => $this->channel,
                'ip'    => $ip,
                'add_time'  => getTime(),
                'update_time' => getTime(),
            );
            $data = array_merge($post, $newData);

            // 数据验证            
            $validate = \think\Loader::validate('Guestbook');
            if(!$validate->batch()->check($data))
            {
                $error = $validate->getError();
                $error_msg = array_values($error);
                $this->error($error_msg[0]);
                exit;
            } else {
                $aid = M('guestbook')->insertGetId($data);
                if ($aid > 0) {
                    $this->saveGuestbookAttr($aid, $typeid);
                }
                $this->success('操作成功！');
                exit;
            }  
        }

        $this->error('表单typeid值丢失！');
        exit;
    }

    /**
     *  给指定留言添加表单值到 guestbook_attr
     * @param int $aid  留言id
     * @param int $typeid  留言栏目id
     */
    private function saveGuestbookAttr($aid, $typeid)
    {  
        // post 提交的属性  以 attr_id _ 和值的 组合为键名    
        $post = I("post.");
        foreach($post as $k => $v)
        {
            $attr_id = str_replace('attr_','',$k);
            if(!strstr($k, 'attr_'))
                continue;                                 

            //$v = str_replace('_', '', $v); // 替换特殊字符
            //$v = str_replace('@', '', $v); // 替换特殊字符
            $v = trim($v);
            $adddata = array(
                'aid'   => $aid,
                'attr_id'   => $attr_id,
                'attr_value'   => filter_line_return($v, '。'),
                'add_time'   => getTime(),
                'update_time'   => getTime(),
            );
            M('GuestbookAttr')->add($adddata);                       
        }
    }
}