<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月29日
 *  系统日志控制器
 */
namespace app\admin\controller\system;

use core\basic\Controller;
use app\admin\model\system\SyslogModel;

class SyslogController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new SyslogModel();
    }

    // 日志列表
    public function index()
    {
        $this->assign('syslogs', $this->model->getList());
        $this->display('system/syslog.html');
    }

    // 清理日志
    public function clear()
    {
        if ($this->model->clearLog()) {
            alert_location('清空成功！', url('/admin/Syslog/index'));
        } else {
            alert_location('清空失败！', url('/admin/Syslog/index'));
        }
    }
}