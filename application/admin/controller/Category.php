<?php


namespace app\admin\controller;
use think\Db;
use think\Exception;
use think\Request;

class Category extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->category = model('Category');
        $this->img = model('Image');
    }

    /**
     * @desc 产品分类
     * @return mixed
     */
    public function category()
    {
        $num = $this->category->getCategory();
        $res = $this->category->categorysInfo();
        $this->assign('count',$num);
        $this->assign('res',$res);
        return $this->fetch();
    }

    /**
     * @desc 产品分类修改
     * @return mixed
     */
    public function category_edit()
    {
        $param = input('param.');
        $id = $param['id'];
        $res = $this->category->getCategoryInfo($id);
        //var_dump($res);die();
        $this->assign('res',$res);
        return $this->fetch();
    }

    /**
     * @desc category修改
     * @return false|string
     */
    public function category_edit_sure()
    {
        $param = input('param.');
        //var_dump($param);die();
        $img_id = $param['img_id'];
        $img_url = '/'.$param['img_thumb_url'];
        $category_id = $param['category_id'];
        $category_name = $param['category_name'];
        Db::startTrans();
        try{
            if ($param['img_thumb_url']){
                $data = [
                    'url' => $img_url,
                    'from' =>1
                ];
                $res = $this->img->imgupdate($img_id,$data);
            }else{
                $res = true;
            }
            if ($category_name){
                $data1 = [
                    'name' => $category_name,
                ];
                $res1 = $this->category->updateCategory($category_id,$data1);
            }else{
                $res1 = true;
            }

            // 提交事务
            Db::commit();
            $data2['code'] = true;
            return json_encode($data2);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $data2['code'] = false;
            $data2['msg'] = $e->getMessage();
            return json_encode($data2);
        }

    }

    /**
     * @desc 产品分类添加
     * @return mixed
     */
    public function category_add()
    {
        return $this->fetch();
    }

    /**
     * @desc 添加产品分类的逻辑
     * @param Request $request
     * @return false|string
     */
    public function category_add_sure(Request $request)
    {
        $param = input('param.');
        $url = '/'.$param['img_thumb_url'];
        $category_name = $param['category_name'];
        $data = [
            'url'=> $url,
            'from'=>1,
        ];
        Db::startTrans();
        try{
            $res = $this->img->insert($data);
            $img_id = (int)$res;
            $data1 = [
                'name' => $category_name,
                'topic_img_id' => $img_id,
            ];
            $res1 = $this->category->insert($data1);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        $response['code'] = true;
        return show(1,'success',$response,200);
    }

    /**
     * @desc 分类下架
     * @return \think\response\Json
     */
    public function category_del()
    {
        $param = input('param.');
        $category_id = $param['category_id'];
        $data = ['type'=>3];
        $res = $this->category->updateCategory($category_id,$data);
        if ($res){
            $response = [
                'code' => true,
                'msg'  => 'success'
            ];
        }else{
            $response=[
                'code' => false,
                'msg'  => 'error'
            ];
        }
        return show(1,'success',$response,200);
    }

    /**
     * @desc 分类发布
     * @return \think\response\Json
     */
    public function category_start()
    {
        $param = input('param.');
        $category_id = $param['category_id'];
        $data = ['type'=>1,'description'=>1];
        $res = $this->category->updateCategory($category_id,$data);
        if ($res){
            $response = [
                'code' => true,
                'msg'  => 'success'
            ];
        }else{
            $response=[
                'code' => false,
                'msg'  => 'error'
            ];
        }
        return show(1,'success',$response,200);
    }

    /**
     * @desc 分类上架
     * @return \think\response\Json
     */
    public function category_load(){
        $param = input('param.');
        $category_id = $param['category_id'];
        $data = ['type'=>2];
        $res = $this->category->updateCategory($category_id,$data);
        if ($res){
            $response = [
                'code' => true,
                'msg'  => 'success'
            ];
        }else{
            $response=[
                'code' => false,
                'msg'  => 'error'
            ];
        }
        return show(1,'success',$response,200);
    }
}