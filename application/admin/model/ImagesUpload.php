<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\model;

use think\Model;

/**
 * 图集图片
 */
class ImagesUpload extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条图集的所有图片
     * @author 小虎哥 by 2018-4-3
     */
    public function getImgUpload($aid, $field = '*')
    {
        $result = db('ImagesUpload')->field($field)
            ->where('aid', $aid)
            ->order('sort_order asc')
            ->select();

        return $result;
    }

    /**
     * 删除单条图集的所有图片
     * @author 小虎哥 by 2018-4-3
     */
    public function delImgUpload($aid = array())
    {
        if (!is_array($aid)) {
            $aid = array($aid);
        }
        $result = db('ImagesUpload')->where(array('aid'=>array('IN', $aid)))->delete();

        return $result;
    }



    /**
     * 保存图集图片
     * @author 小虎哥 by 2018-4-3
     */
    public function saveimg($aid, $post = array())
    {
        $imgupload = isset($post['imgupload']) ? $post['imgupload'] : array();
        if (!empty($imgupload) && count($imgupload) > 1) {
            array_pop($imgupload); // 弹出最后一个

            // 删除产品图片
            $this->delImgUpload($aid);

             // 添加图片
            $data = array();
            $sort_order = 0;
            foreach($imgupload as $key => $val)
            {
                if($val == null || empty($val))  continue;
                
                $img_info = array();
                if (is_http_url($val)) {
                    $imgurl = $val;
                } else {
                    $imgurl = ROOT_PATH.ltrim($val, '/');
                }
                $img_info = @getimagesize($imgurl);
                $width = isset($img_info[0]) ? $img_info[0] : 0;
                $height = isset($img_info[1]) ? $img_info[1] : 0;
                $type = isset($img_info[2]) ? $img_info[2] : 0;
                $attr = isset($img_info[3]) ? $img_info[3] : '';
                $mime = isset($img_info['mime']) ? $img_info['mime'] : '';
                $filesize = @filesize($val);
                $title = !empty($post['title']) ? $post['title'] : '';
                ++$sort_order;
                $data[] = array(
                    'aid' => $aid,
                    'title' => $title,
                    'image_url'   => $val,
                    'width' => $width,
                    'height' => $height,
                    'filesize'  => ($filesize ? $filesize : 0),
                    'mime'  => $mime,
                    'sort_order'    => $sort_order,
                    'add_time' => getTime(),
                );
            }
            if (!empty($data)) {
                M('ImagesUpload')->insertAll($data);

                // 没有封面图时，取第一张图作为封面图
                $litpic = isset($post['litpic']) ? $post['litpic'] : '';
                if (empty($litpic)) {
                    $litpic = $data[0]['image_url'];
                    M('archives')->where(array('aid'=>$aid))->update(array('litpic'=>$litpic, 'update_time'=>getTime()));
                }
            }
            delFile(UPLOAD_PATH."images/thumb/$aid"); // 删除缩略图
        }
    }
}