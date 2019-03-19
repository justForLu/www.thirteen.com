<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月8日
 *  
 */
namespace app\home\model;

use core\basic\Model;

class DoModel extends Model
{

    // 新增访问
    public function addVisits($id)
    {
        $data = array(
            'visits' => '+=1'
        );
        parent::table('ay_content')->where("id=$id")->update($data);
    }

    // 新增喜欢
    public function addLikes($id)
    {
        $data = array(
            'likes' => '+=1'
        );
        parent::table('ay_content')->where("id=$id")->update($data);
    }

    // 新增喜欢
    public function addOppose($id)
    {
        $data = array(
            'oppose' => '+=1'
        );
        parent::table('ay_content')->where("id=$id")->update($data);
    }
}