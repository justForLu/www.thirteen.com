<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\controller;

use think\Page;

class AdPosition extends Base
{
    private $ad_position_system_id = array(); // 系统默认位置ID，不可删除

    public function index()
    {
        $list = array();
        $get = I('get.');
        $keywords = I('keywords/s');
        $condition = array();
        // 应用搜索条件
        foreach (['keywords'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                if ($key == 'keywords') {
                    $condition['a.title'] = array('LIKE', "%{$get[$key]}%");
                } else {
                    $tmp_key = 'a.'.$key;
                    $condition[$tmp_key] = array('eq', $get[$key]);
                }
            }
        }

        $adPositionM =  M('ad_position');
        $count = $adPositionM->alias('a')->where($condition)->count();// 查询满足要求的总记录数
        $Page = new Page($count, config('paginate.list_rows'));// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $adPositionM->alias('a')->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        // 获取指定位置的广告数目
        $cid = get_arr_column($list, 'id');
        $ad_list = M('ad')->field('pid, count(id) AS has_children')->where('pid', 'in', $cid)->group('pid')->getAllWithIndex('pid');
        $this->assign('ad_list', $ad_list);

        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('list',$list);// 赋值数据集
        $this->assign('pager',$Page);// 赋值分页对象
        return $this->fetch();
    }
    
    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {
            $post = I('post.');

            $map = array(
                'title' => trim($post['title']),
            );
            if(M('ad_position')->where($map)->count() > 0){
                $this->error('该广告位名称已存在，请检查', U('AdPosition/index'));
            }

            $data = array(
                'title'    => trim($post['title']),
                'width' => $post['width'],
                'height' => $post['height'],
                'intro' => $post['intro'],
                'add_time'           => getTime(),
                'update_time'           => getTime(),
            );
            $r = M('ad_position')->insert($data);

            if ($r) {
                adminLog('新增广告位：'.$post['title']);
                $this->success("操作成功", U('AdPosition/index'));
            } else {
                $this->error("操作失败", U('AdPosition/index'));
            }
            exit;
        }

        return $this->fetch();
    }

    
    /**
     * 编辑
     */
    public function edit()
    {
        if (IS_POST) {
            $post = I('post.');
            if(!empty($post['id'])){

                if(array_key_exists($post['id'], $this->ad_position_system_id)){
                    $this->error("不可更改系统预定义位置", U('AdPosition/edit',array('id'=>$post['id'])));
                }
                $map = array(
                    'id' => array('NEQ', $post['id']),
                    'title' => trim($post['title']),
                );
                if(M('ad_position')->where($map)->count() > 0){
                    $this->error('该广告位名称已存在，请检查', U('AdPosition/index'));
                }

                $data = array(
                    'id'       => $post['id'],
                    'title'    => trim($post['title']),
                    'width' => $post['width'],
                    'height' => $post['height'],
                    'intro' => $post['intro'],
                    'update_time'       => getTime(),
                );
                $r = M('ad_position')->update($data);
            }
            if ($r) {
                adminLog('编辑广告位：'.$post['title']);
                $this->success("操作成功", U('AdPosition/index'));
            } else {
                $this->error("操作失败");
            }
        }

        $assign_data = array();

        $id = I('id/d');
        $field = M('ad_position')->field('a.*')
            ->alias('a')
            ->where(array('a.id'=>$id))
            ->find();
        if (empty($field)) {
            $this->error('广告位不存在，请联系管理员！');
            exit;
        }
        $assign_data['field'] = $field;

        $this->assign($assign_data);
        return $this->fetch();
    }

    
    /**
     * 删除
     */
    public function del()
    {
        $id_arr = I('del_id/a');
        $id_arr = eyIntval($id_arr);
        if(!empty($id_arr)){
            foreach ($id_arr as $key => $val) {
                if(array_key_exists($val, $this->ad_position_system_id)){
                    $this->error('系统预定义，不能删除');
                }
            }

            $ad_count = M('ad')->where('pid','IN',$id_arr)->count();
            if ($ad_count > 0){
                $this->error('该位置下有广告，不允许删除，请先删除该位置下的广告');
            }  

            $r = M('ad_position')->where('id','IN',$id_arr)->delete();
            if ($r) {
                adminLog('删除广告位-id：'.implode(',', $id_arr));
                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }
        }else{
            $this->error('参数有误');
        }
    }
}