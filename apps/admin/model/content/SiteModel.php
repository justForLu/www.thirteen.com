<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月24日
 *  站点配置模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class SiteModel extends Model
{

    // 获取系统配置信息
    public function getList()
    {
        return parent::table('ay_site')->where("acode='" . session('acode') . "'")->find();
    }

    // 检查系统配置信息
    public function checkSite()
    {
        return parent::table('ay_site')->where("acode='" . session('acode') . "'")->find();
    }

    // 增加系统配置信息
    public function addSite($data)
    {
        return parent::table('ay_site')->insert($data);
    }

    // 修改系统配置信息
    public function modSite($data)
    {
        return parent::table('ay_site')->where("acode='" . session('acode') . "'")->update($data);
    }

    // 系统数据库版本
    public function getMysql()
    {
        return parent::one('SELECT VERSION()', MYSQLI_NUM);
    }
}