<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 22:11
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use think\Exception;
use think\Request;
/**
 * 小程序登录及首页
 * Class NewIndex
 * @package app\admin\controller
 */
class NewIndex extends Common
{
    private $obj;

    public function _initialize()
    {
            $this->obj = model('NewAdmin');
            $this->order = model('NewOrder');
            $this->product = model('NewProduct');
            $this->banner = model('Banner');
            $this->img = model('Image');
            $this->category = model('Category');
            $this->theme = model('Theme');
    }
    /**
     * @desc 登录页面
     * @return mixed
     */
    public function login()
    {
        if (Session::has('admin_id')){
            $this->redirect('admin/index/index');
        }else{
            return $this->fetch();
        }
    }

    /**
     * @desc 后台登录校验
     */
    public function checklogin()
    {
        if (!request()->isPost()){
            return false;
        }
        $param = input('param.');
        $result = $this->obj->getUser($param['id']);
        //var_dump($result);
        if ($result['app_id'] == $param['id'] && $result['app_secret'] == $param['pwd']){
            Session::set('admin_id',$result['app_id']);
            $this->redirect('admin/index/index');
        }else{
            $this->redirect('admin/index/login');
        }
    }

    /**
     * @desc 退出登录
     */
    public function out()
    {
        Session::delete('admin_id');
        $this->redirect('admin/index/login');
    }

    /**
     * @desc 后台首页
     * @return mixed
     */
    public function index()
    {
        $admin_user = Session::get('admin_id');
        $this->assign('admin_name',$admin_user);
        return $this->fetch();
    }

    /**
     * @desc banner
     * @return mixed
     */
    public function banner()
    {
        $res = $this->banner->bannersInfo();
        $count = $this->banner->getBanners();
        //halt($res);
        $this->assign(['count'=>$count,'res'=>$res]);
        return $this->fetch();
    }

    /**
     * @desc banner修改
     * @return mixed
     */
    public function banner_edit(Request $request)
    {
        $param = input('param.');
        $banner_id = $param['id'];
        $banner_info = $this->banner->getBannerIfo($banner_id);
        $this->assign('banner_info',$banner_info);
        return $this->fetch();
    }

    /**
     * @desc 确认banner修改
     * @param Request $request
     */
    public function banner_edit_sure()
    {
        $param = input('param.');
        $img_url = '/'. $param['img_thumb_url'];
        $img_id = $param['img_id'];
        $data = [
            'url'=> $img_url
        ];
        $res = $this->banner->updateBannerImg($img_id,$data);
        if ($res){
            $data1['code'] = true;
            return json_encode($data1);
        }
    }

    /**
     * @desc 添加banner
     * @return mixed
     */
    public function banner_add()
    {
        return $this->fetch();
    }

    /**
     * @desc banner图上传
     * @param Request $request
     * @return string
     */
    public function banner_add_store(Request $request)
    {
        try{
            //获取图片对象
            $filetemp = $request->file('file');
            //存放服务器上地址
            $serverFile = $filetemp->move(ROOT_PATH . 'public' . DS . 'images');
            //缩略图
            //访问地址
            $imageUrl = $serverFile->getSaveName();
            $ajaxJson['success'] = true;
            $ajaxJson['msg'] = $imageUrl;
            $ajaxJson['thumb_msg'] = $imageUrl;
        }catch (\Exception $e){
            $ajaxJson['success'] = false;
        }
        return json_encode($ajaxJson);
    }

    /**
     * @desc 新增banner
     * @param Request $request
     */
    public function banner_add_sure(Request $request)
    {
        $param = input('param.');
        $img_thumb_url = '/'.$param['img_thumb_url'];
        if ($img_thumb_url){
            $data['url'] = $img_thumb_url;
            $data['from'] = 1;
        }
        Db::startTrans();
        try{
            $res = $this->img->insert($data);
            $img_id = (int)$res;
            $data1 = [
                'img_id' => $img_id,
                'key_word' => 1,
                'type' => 1,
                'banner_id' => 1,
            ];
            $res1 = $this->banner->insert($data1);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        $data['code'] = true;
        $data['img_thumb_url'] = $img_thumb_url;
        $data['mes'] = $param;
        return json_encode($data);
    }

    /**
     * @desc 删除banner
     * @return string
     */
    public function banner_del()
    {
        $param = input('param.');
        $banner_id = $param['banner_id'];
        $res = $this->banner->delBanner($banner_id);
        if ($res){
            $data['code'] = true;
            return json_encode($data);
        }
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
     * @desc 图片上传
     * @param Request $request
     * @param $width
     * @param $height
     * @return string
     */
    public function imageUpload(Request $request)
    {
        try{
            //获取图片对象
            $filetemp = $request->file('file');
            //存放服务器上地址
            $serverFile = $filetemp->move(ROOT_PATH . 'public' . DS . 'images');
            //缩略图
            //访问地址
            $imageUrl = $serverFile->getSaveName();
            $ajaxJson['success'] = true;
            $ajaxJson['msg'] = $imageUrl;
            $ajaxJson['thumb_msg'] = $imageUrl;
        }catch (\Exception $e){
            $ajaxJson['success'] = false;
        }
        return json_encode($ajaxJson);
    }

    /**
     * @desc 添加产品分类的逻辑
     * @param Request $request
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
        $data['code'] = true;
        return json_encode($data);
    }

    /**
     * @desc 专题列表
     * @return mixed
     */
    public function theme()
    {
        $count = $this->theme->getTheme();
        $res = $this->theme->getThemeInfo();
        $this->assign('res',$res);
        $this->assign('count',$count);
        return $this->fetch();
    }

    /**
     * @desc 专题修改
     * @return mixed
     */
    public function theme_edit()
    {
        $param = input('param.');
        $theme = $this->theme->getThemInfo($param['id']);
        $this->assign('theme',$theme);
        return $this->fetch();
    }

    public function theme_edit_sure()
    {
        $param = input('param.');
        $id = $param['theme_id'];
        $url = $param['img_thumb_url'];
        $data = [
            'url'=> '/'.$url,
            'from'=>1,
        ];
        if($id && $url){
            $img_id = $this->img->insert($data);
            $img_id = (int)$img_id;
            $data1 = [
                'topic_img_id' =>$img_id
            ];
            $res = $this->theme->updateThemeImg($id,$data1);
            if($res){
                $data['code'] = true;
                return json_encode($data);
            }else{
                $data['code'] = false;
                return json_encode($data);
            }
        }else{
            $data['code'] = false;
            return json_encode($data);
        }
    }

    /**
     * @desc 专题下产品
     * @return mixed
     */
    public function theme_product_list()
    {
        return $this->fetch();
    }

    /**
     * @desc 专题产品添加
     * @return mixed
     */
    public function theme_product_add()
    {
        return $this->fetch();
    }

    /**
     * @desc 专题产品删除
     * @return mixed
     */
    public function theme_product_del()
    {
        return $this->fetch();
    }

    /**
     * @desc 产品列表
     * @return mixed
     */
    public function product()
    {
        $count = $this->product->count();
        $info=$this->product->getProductInfo();
        $this->assign('res',$info);
        $this->assign('count',$count);
        return $this->fetch();
    }

    /**
     * @desc 添加商品
     * @return mixed
     */
    public function product_add()
    {
        $param = input('param.');
        $theme = $this->theme->ProductandTheme();
        $category = $this->category->ProductandCategory();
        $this->assign('res1',$theme);
        $this->assign('res2',$category);
        return $this->fetch();
    }

    /**
     * @desc 添加商品逻辑
     */
    public function product_add_sure()
    {
        $param = input('param.');
        //保存照片表数据
        $img_url = '/'.$param['img_thumb_url'];
        $data = [
            'url' => $img_url,
            'from'=>1
        ];
        Db::startTrans();
        try{
            $img = $this->img->insert($data);
            $img_id = (int)$img;
            $data1 = [
                'name' => $param['pname'],
                'price'=>$param['price'],
                'stock'=>$param['stock'],
                'category_id'=>$param['category'],
                'main_img_url' =>$img_url,
                'img_id'       => $img_id
            ];
            $res = $this->product->insert($data1);
            $product_id = (int)$res;
            $data2 = [
                'theme_id' => $param['theme'],
                'product_id' => $product_id
            ];
            $res1 = $this->product->insertTheme($data2);
            Db::commit();
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            $data3['code'] = false;
            return json_encode($data3);
        }
        $data4['code'] = true;
        return json_encode($data4);

    }

    /**
     * @desc 产品修改
     * @return mixed
     */
    public function product_edit()
    {
        $param = input('param.');
        $id = $param['id'];
        $res = $this->product->getProduct($id);
        $theme = $this->theme->ProductandTheme();
        $category = $this->category->ProductandCategory();
        $this->assign('res1',$theme);
        $this->assign('res2',$category);
        $this->assign('res',$res);
        return $this->fetch();
    }

    public function product_edit_sure()
    {
        $param = input('param.');
        $id = $param['product_id'];
        $name = $param['pname'];
        $price = $param['price'];
        $stock = $param['stock'];
        $category_id = $param['category'];
        $theme = $param['theme'];
        $url = '/'.$param['img_thumb_url'];
        $main_img_id = $param['main_img_id'];
        Db::startTrans();
        try{
            $data=[
                'name' => $name,
                'price'=> $price,
                'stock'=> $stock,
                'category_id'=> $category_id,
                'main_img_url'=> $url,
                'from'=>1,
                'img_id'=>$main_img_id
            ];
            $res = $this->product->updateProduct($id,$data);
            $data1=[
                'theme_id'=>$theme
            ];
            $res1 = $this->product->updateProductAndTheme($id,$data1);
            Db::commit();
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            $data3['code'] = false;
            return json_encode($data3);
        }
        $data4['code'] = true;
        return json_encode($data4);
    }

    /**
     * @desc 产品下架
     * @return mixed
     */
    public function product_del()
    {
        $param = input('param.');
        $product_id = $param['product_id'];
        $data['from'] = 2;
        $res = $this->product->updateProduct($product_id,$data);
        if ($res){
            $data1['code'] = true;
            return json_encode($data1);
        }else{
            $data1['code'] = false;
            return json_encode($data1);
        }
    }

    /**
     * @desc 订单列表
     * @return mixed
     */
    public function order()
    {
        return $this->redirect('https://www.gantanggo.com/cms/pages/login.html');
    }

    /**
     * @param 发货信息模板消息发送
     */
    public function send_message()
    {

    }


}