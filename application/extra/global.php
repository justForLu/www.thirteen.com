<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

return array(
    // 一天的时间戳
    'one_day_time'  => 86400,
    // 发送短信默认有效时间
    'sms_default_time_out' => 120,
    // 发送邮箱默认有效时间
    'email_default_time_out' => 180,
    // 栏目最多级别
    'arctype_max_level' => 3,
    // 模型标识
    'channeltype_list' => array(
        // 文章模型标识
        'article' => 1,
        // 产品模型标识
        'product' => 2,
        // 图片集模型
        'images'    => 3,
        // 下载模型标识
        'download' => 4,
        // 单页模型标识
        'single' => 6,
        // 留言模型标识
        'guestbook' => 8,
    ),
    // 发布文档的模型ID
    'allow_release_channel' => array(1,2,3,4),
    // 广告类型
    'ad_media_type' => array(
        1   => '图片',
        // 2   => 'flash',
        // 3   => '文字',
    ),
    'attr_input_type_arr' => array(
        0   => '单行文本',
        1   => '下拉框',
        2   => '多行文本',
        3   => 'HTML文本',
    ),
    // 栏目自定义字段的channel_id值
    'arctype_channel_id' => -99,
    // 栏目表原始字段
    'arctype_table_fields' => array('id','channeltype','current_channel','parent_id','typename','dirname','dirpath','englist_name','grade','typelink','litpic','templist','tempview','seo_title','seo_keywords','seo_description','sort_order','is_hidden','is_part','status','lang','admin_id','is_del','add_time','update_time'),
    // 网络图片扩展名
    'image_ext' => 'jpg,jpeg,gif,bmp,ico,png',
);
