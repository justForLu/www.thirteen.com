<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\model;

use think\Model;
// use app\admin\logic\WeappLogic;

/**
 * 插件模型
 */
class Weapp extends Model
{
    public $weappLogic;
    
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
        // $this->weappLogic = new WeappLogic();
    }

    /**
     * 获取插件列表
     */
    public function getList($where = array()){
        $result = M('weapp')->where($where)->getAllWithIndex('code');
        foreach ($result as $key => $val) {
            $config = include WEAPP_PATH.$val['code'].DS.'config.php';
            $val['config'] = json_encode($config);
            $result[$key] = $val;
        }
        return $result;
    }
}