<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\home\validate;
use think\Validate;

class Guestbook extends Validate
{
    // 验证规则
    protected $rule = array(
        'typeid'    => 'require',
    );

    protected $message = array(
        'typeid.require' => '表单typeid值丢失！',
    );
}