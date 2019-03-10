<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

/**
 * 检验登陆
 * @param
 * @return bool
 */
function is_adminlogin(){
    $admin_id = session('admin_id');
    if(isset($admin_id) && $admin_id > 0){
        return $admin_id;
    }else{
        return false;
    }
}