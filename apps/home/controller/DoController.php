<?php
/**
 * @copyright (C)2019 拾叁网络 Ltd.
 * @license This is the official website of 13 Network Technology Co., Ltd
 * @author JackLu
 * @email 1120714124@qq.com
 * @date 2019年3月8日
 *  
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\DoModel;

class DoController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new DoModel();
    }

    /**
     * 多语言切换
     */
    public function area()
    {
        $lg = request('lg', 'var');
        if ($lg) {
            $lgs = $this->config('lgs');
            if (isset($lgs[$lg])) {
                cookie('lg', $lg);
            }
            location(SITE_DIR . '/');
        }
    }

    /**
     * 文章访问量累计
     */
    public function visits()
    {
        if (! ! $id = get('id', 'int')) {
            $this->model->addVisits($id);
            echo 'var ok;'; // 避免前端浏览器报js错
        } else {
            echo 'var error;'; // 避免前端浏览器报js错
        }
    }

    /**
     * 点赞
     */
    public function likes()
    {
        if (($id = get('id', 'int')) && ! cookie('likes_' . $id)) {
            $this->model->addLikes($id);
            cookie('likes_' . $id, true, 31536000, null, null, null, null);
        }
        location('-1');
    }

    /**
     * 获取是否点赞
     */
    public function isLikes()
    {
        if (($id = get('id', 'int')) && cookie('likes_' . $id)) {
            return json(1, 'yes');
        } else {
            return json(0, 'no');
        }
    }

    /**
     * 反对
     */
    public function oppose()
    {
        if (($id = get('id', 'int')) && ! cookie('oppose_' . $id)) {
            $this->model->addOppose($id);
            cookie('oppose_' . $id, true, 31536000, null, null, null, null);
        }
        location('-1');
    }

    /**
     * 获取是否反对
     */
    public function isOppose()
    {
        if (($id = get('id', 'int')) && cookie('oppose_' . $id)) {
            return json(1, 'yes');
        } else {
            return json(0, 'no');
        }
    }
}



