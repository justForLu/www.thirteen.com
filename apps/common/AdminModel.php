<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月28日
 *  应用公共模型类
 */
namespace app\common;

use core\basic\Model;

class AdminModel extends Model
{

    // 获取配置参数
    public function getConfig()
    {
        return parent::table('ay_config')->column('value', 'name');
    }

    // 获取站点配置信息
    public function getSite()
    {
        return parent::table('ay_site')->where("acode='" . session('acode') . "'")->find();
    }

    // 获取公司配置信息
    public function getCompany()
    {
        return parent::table('ay_company')->where("acode='" . session('acode') . "'")->find();
    }
}