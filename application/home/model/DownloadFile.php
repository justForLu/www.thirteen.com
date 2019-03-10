<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\model;

use think\Model;

/**
 * 下载文件
 */
class DownloadFile extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条下载文章的所有文件
     * @author 小虎哥 by 2018-4-3
     */
    public function getDownFile($aid, $field = '*')
    {
        $result = db('DownloadFile')->field($field)
            ->where('aid', $aid)
            ->order('sort_order asc')
            ->select();

        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $downurl = url('home/View/downfile', array('id'=>$val['file_id'], 'uhash'=>$val['uhash']));
                $result[$key]['downurl'] = $downurl;
            }
        }

        return $result;
    }
}