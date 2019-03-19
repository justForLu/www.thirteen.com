<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月29日
 *  日志模型类
 */
namespace app\admin\model\system;

use core\basic\Model;

class SyslogModel extends Model
{

    // 获取日志列表
    public function getList()
    {
        return parent::table('ay_syslog')->order('id DESC')
            ->page()
            ->select();
    }

    // 删除全部
    public function clearLog()
    {
        return parent::table('ay_syslog')->delete();
    }
}
