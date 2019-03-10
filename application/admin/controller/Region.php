<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;

use think\Page;

class Region extends Base
{

    /**
    * 获取子类列表
    */  
    public function ajax_get_region($pid = 0, $text = ''){
        $data = model('Region')->getList($pid);
        $html = "<option value=''>--".urldecode($text)."--</option>";
        foreach($data as $key=>$val){
            $html.="<option value='".$val['id']."'>".$val['name']."</option>";
        }

        return $html;
    }
}