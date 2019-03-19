<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月8日
 *  
 */
namespace app\api\controller;

use core\basic\Controller;
use app\api\model\DoModel;

class DoController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new DoModel();
    }

    // 点赞
    public function likes()
    {
        if (! ! $id = request('id', 'int')) {
            $this->model->addLikes($id);
            json(1, '点赞成功');
        } else {
            json(0, '点赞失败');
        }
    }

    // 反对
    public function oppose()
    {
        if (! ! $id = request('id', 'int')) {
            $this->model->addOppose($id);
            json(1, '反对成功');
        } else {
            json(0, '反对失败');
        }
    }
}



