<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月24日
 *  公司信息模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class CompanyModel extends Model
{

    // 获取公司信息
    public function getList()
    {
        return parent::table('ay_company')->where("acode='" . session('acode') . "'")->find();
    }

    // 检查公司信息
    public function checkCompany()
    {
        return parent::table('ay_company')->where("acode='" . session('acode') . "'")->find();
    }

    // 增加公司信息
    public function addCompany($data)
    {
        return parent::table('ay_company')->insert($data);
    }

    // 修改公司信息
    public function modCompany($data)
    {
        return parent::table('ay_company')->where("acode='" . session('acode') . "'")->update($data);
    }
}