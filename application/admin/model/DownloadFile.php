<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\model;

use think\Model;

/**
 * 下载文件
 */
class DownloadFile extends Model
{
    //初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取单条下载文章的所有文件
     * @author 小虎哥 by 2018-4-3
     */
    public function getDownFile($aid, $field = '*')
    {
        $result = db('DownloadFile')->field($field)
            ->where('aid', $aid)
            ->order('sort_order asc')
            ->select();

        return $result;
    }

    /**
     * 删除单条下载文章的所有文件
     * @author 小虎哥 by 2018-4-3
     */
    public function delDownFile($aid = array())
    {
        if (!is_array($aid)) {
            $aid = array($aid);
        }
        $result = db('DownloadFile')->where(array('aid'=>array('IN', $aid)))->delete();

        return $result;
    }



    /**
     * 保存下载文章的文件
     * @author 小虎哥 by 2018-4-3
     */
    public function savefile($aid, $post = array())
    {
        $fileupload = isset($post['fileupload']) ? $post['fileupload'] : array();
        if (!empty($fileupload)) {

             $DownFileArr = M('DownloadFile')->where("aid = $aid")->getField('file_id,file_url'); // 查出所有已经存在的文件

             // 删除文件
             $file_ids = array();
             foreach($DownFileArr as $key => $val)
             {
                if (!in_array($val, $fileupload)) {
                    array_push($file_ids, $key);
                }
             }
             if (!empty($file_ids)) {
                db('DownloadFile')->where(array('file_id'=>array('IN', $file_ids)))->delete();
             }

             // 添加文件
            $data = array();
            $sort_order = 0;
            foreach($fileupload as $key => $val)
            {
                if($val == null || empty($val))  continue;                                  
                if(!in_array($val, $DownFileArr))
                {                 
                    $title = !empty($post['title']) ? $post['title'] : '';
                    $file_size = isset($post['fileSize'][$key]) ? $post['fileSize'][$key] : 0;
                    $file_mime = isset($post['fileMime'][$key]) ? $post['fileMime'][$key] : '';
                    $uhash = isset($post['uhash'][$key]) ? $post['uhash'][$key] : '';
                    $md5file = isset($post['md5file'][$key]) ? $post['md5file'][$key] : '';
                    $file_ext = pathinfo($val, PATHINFO_EXTENSION);
                    $file_name = pathinfo($val, PATHINFO_BASENAME);
                    ++$sort_order;
                    $data[] = array(
                        'aid' => $aid,
                        'title' => $title,
                        'file_url'   => $val,
                        'file_size'  => $file_size,
                        'file_ext'  => $file_ext,
                        'file_name'  => $file_name,
                        'file_mime'  => $file_mime,
                        'uhash'  => $uhash,
                        'md5file'  => $md5file,
                        'sort_order'    => $sort_order,
                        'add_time' => getTime(),
                    );
                }                 
            }
            if (!empty($data)) {
                M('DownloadFile')->insertAll($data);
            }
        } else {
            M('DownloadFile')->where('aid',$aid)->delete();
        }
    }
}