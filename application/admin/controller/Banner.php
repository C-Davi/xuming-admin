<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use think\Exception;
use think\Request;

/**
 * @desc banner模块
 * Class Banner
 * @package app\admin\controller
 */
class Banner extends Common
{
    private $banner;
    private  $img;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->banner = model('Banner');
        $this->img = model('Image');
    }

    /**
     * @desc banner  首页
     * @return mixed
     */
    public function index()
    {
        $res = $this->banner->bannersInfo();
//        halt($res);die();
        $count = count($res);
        $this->assign(['count'=>$count,'res'=>$res]);
        return $this->fetch();
    }

    /**
     * @desc banner添加
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * @desc banner修改展示
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        $param = input('param.');
        $banner_id = $param['id'];
        $banner_info = $this->banner->getBannerIfo($banner_id);
        $this->assign('banner_info',$banner_info);
        return $this->fetch();
    }

    /**
     * @desc banner确认修改
     * @return array
     */
    public function  banner_edit_sure()
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
            return show(1,'success',$data1,200);
        }
    }

    /**
     * @desc banner 图片上传
     * @param Request $request
     * @return array
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
        return show(1,'success',$ajaxJson,200);
    }

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
        return show(1,'success',$data,200);
    }

    /**
     * @desc banner删除
     * @return array
     */
    public function banner_del()
    {
        $param = input('param.');
        $banner_id = $param['banner_id'];
        $res = $this->banner->delBanner($banner_id);
        if ($res){
            $data['code'] = true;
            return show(1,'success',$data,200);
        }
    }
}