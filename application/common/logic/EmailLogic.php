<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\common\logic;

/**
 * Description of SmsLogic
 *
 * 邮件类
 */
class EmailLogic 
{
    private $config;
    
    public function __construct() 
    {
        $this->config = tpCache('email') ?: [];
    }

    /**
     * 置换邮件模版内容
     * @param unknown $scene
     */
    public function replaceContent($scene, $params)
    {
        $emailTemp = M('email_template')->where("send_scene", $scene)->find();
        $username = $content = '';
        if (is_array($params)) {
            $content = !empty($params['content']) ? $params['content'] : false;
            $username = !empty($params['username']) ? $params['username'] : false;
        } else {
            $content = $params;
        }

        $emailParams = array(
            1 => "{\"content\":\"$content\"}", //1. 通用
            2 => "{\"username\":\"$username\",\"content\":\"$content\"}", //2. 小程序审核通知
            3 => "{\"content\":\"$content\"}", //3. 小程序入驻通知
            4 => "{\"content\":\"$content\"}", //4. 小程序取消授权通知
        );

        $emailParam = $emailParams[$scene];

        //置换邮件模版内容
        $msg = $emailTemp['tpl_content'];
        $params_arr = json_decode($emailParam);
        foreach ($params_arr as $k => $v) {
            $msg = str_replace('${' . $k . '}', $v, $msg);
        }

        return $msg;
    }
}
