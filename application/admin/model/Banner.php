<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 10:44
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class Banner extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * @desc 获取banner总数
     * @return int|string
     */
    public function getBanners()
    {
        $res = Db::name('banner_item')->count();
        return $res;
    }

    /**
     * @desc banner信息
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function bannersInfo()
    {
        $res = Db::view('banner_item','id,img_id,key_word,type,banner_id,created_time,update_time')
            ->view('image','url','banner_item.img_id = image.id')
            ->where('banner_item.type',1)
            ->select();
        return $res;
    }

    /**
     * @desc 获取指定的banner信息
     * @param $banner_item
     * @return array|false|\PDOStatement|string|Model
     */
    public function getBannerIfo($banner_item)
    {
        $res = Db::name('banner_item')->where('id',$banner_item)->find();
        if ($res){
            $img_id = $res['img_id'];
        }
        $res1 = Db::name('image')->where('id',$img_id)->find();
        return $res1;
    }

    /**
     * @desc 插入banner
     * @param array|mixed $data
     * @return int|string
     */
    public function insert($data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['created_time'] = date('Y-m-d H:i:s');
        $res = Db::name('banner_item')->insert($data);
        return $res;
    }

    /**
     * @desc 更新banner的img信息
     * @param $id
     * @param $data
     * @return int|string
     */
    public function updateBannerImg($id,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('image')->where('id',$id)->update($data);
        return $res;
    }

    /**
     * @desc 删除
     * @param $id
     * @return mixed
     */
    public function delBanner($id)
    {
        $data = [
            'delete_time' => date('Y-m-d H:i:s'),
            'type'         => 2,
        ];
        $res = Db::name('banner_item')->where('id',$id)->update($data);
        return $res;
    }
}