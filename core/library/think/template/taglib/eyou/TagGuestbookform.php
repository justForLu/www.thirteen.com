<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace think\template\taglib\eyou;

/**
 * 留言表单
 */
class TagGuestbookform extends Base
{
    public $tid = '';
    
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->tid = I("param.tid/s", ''); // 应用于栏目列表
        /*tid为目录名称的情况下*/
        $this->tid = $this->getTrueTypeid($this->tid);
        /*--end*/
    }

    /**
     * 获取留言表单
     * @author wengxianhu by 2018-4-20
     */
    public function getGuestbookform($typeid = '', $type = 'default')
    {
        $typeid = !empty($typeid) ? $typeid : $this->tid;

        if (empty($typeid)) {
            echo '标签guestbookform报错：缺少属性 typeid 值。';
            return false;
        }

        $result = false;

        /*当前栏目下的表单属性*/
        $row = M('guestbook_attribute')->where('typeid', $typeid)->order('sort_order asc, attr_id asc')->select();
        /*--end*/
        if (empty($row)) {
            echo '标签guestbookform报错：该栏目下没有新增表单属性。';
            return false;
        } else {
            $newAttribute = array();
            $attr_input_type_1 = 1; // 兼容v1.1.6之前的版本
            foreach ($row as $key => $val) {
                // $newKey = $key + 1;
                $attr_id = $val['attr_id'];
                /*字段名称*/
                $name = 'attr_'.$attr_id;
                $newAttribute[$name] = $name;
                /*--end*/
                /*表单提示文字*/
                $itemname = 'itemname_'.$attr_id;
                $newAttribute[$itemname] = $val['attr_name'];
                /*--end*/
                /*针对下拉选择框*/
                if ($val['attr_input_type'] == 1) {
                    $tmp_option_val = explode(PHP_EOL, $val['attr_values']);
                    $options = array();
                    foreach($tmp_option_val as $k2=>$v2)
                    {
                        $tmp_val = array(
                            'value' => $v2,
                        );
                        array_push($options, $tmp_val);
                    }
                    $newAttribute['options_'.$attr_id] = $options;

                    /*兼容v1.1.6之前的版本*/
                    if (1 == $attr_input_type_1) {
                        $newAttribute['options'] = $options;
                    }
                    ++$attr_input_type_1;
                    /*--end*/
                }
                /*--end*/
            }

            $hidden = '<input type="hidden" name="typeid" value="'.$typeid.'" />';
            $newAttribute['hidden'] = $hidden;

            $action = url('home/Lists/gbook_submit');
            $newAttribute['action'] = $action;

            $result[0] = $newAttribute;
        }
        
        return $result;
    }

    /**
     * 动态获取留言栏目属性输入框 根据不同的数据返回不同的输入框类型
     * @param int $typeid 留言栏目id
     */
    public function getAttrInput($typeid)
    {
        header("Content-type: text/html; charset=utf-8");
        $attributeList = M('GuestbookAttribute')->where("typeid = $typeid")->order('sort_order asc')->select();
        $form_arr = array();
        $i = 1;
        foreach($attributeList as $key => $val)
        {
            $str = "";
            switch ($val['attr_input_type']) {
                case '0':
                    $str = "<input class='guest-input ".$this->inputstyle."' id='attr_".$i."' type='text' value='".$val['attr_values']."' name='attr_{$val['attr_id']}[]' placeholder='".$val['attr_name']."'/>";
                    break;
                
                case '1':
                    $str = "<select class='guest-select ".$this->inputstyle."' id='attr_".$i."' name='attr_{$val['attr_id']}[]'><option value=''>无</option>";
                    $tmp_option_val = explode(PHP_EOL, $val['attr_values']);
                    foreach($tmp_option_val as $k2=>$v2)
                    {
                        $str .= "<option value='{$v2}'>{$v2}</option>";
                    }
                    $str .= "</select>";
                    break;
                
                case '2':
                    $str = "<textarea class='guest-textarea ".$this->inputstyle."' id='attr_".$i."' cols='40' rows='3' name='attr_{$val['attr_id']}[]' placeholder='".$val['attr_name']."'>".$val['attr_values']."</textarea>";
                    break;
                
                default:
                    # code...
                    break;
            }

            $i++;

            $form_arr[$key] = array(
                'value' => $str,
                'attr_name' => $val['attr_name'],
            );
        }        
        return  $form_arr;
    }
}