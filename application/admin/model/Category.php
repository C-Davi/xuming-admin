<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 20:34
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @desc 获取所有的category
     * @return int|string
     */
    public function getCategory()
    {
        $res = Db::name('category')->count();
        return $res;
    }

    /**
     * @desc category信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function categorysInfo()
    {
        $res = Db::view('category','id,name,topic_img_id,created_time,update_time,type')
            ->view('image','url','category.topic_img_id = image.id')
            ->select();
        return $res;
    }

    /**
     * @desc 获得指定的产品分类
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCategoryInfo($id)
    {
        $res = Db::name('category')->where('id',$id)->find();
        $data = [
            'id' => $res['id'],
            'name' => $res['name'],
            'topic_img_id' => $res['topic_img_id']
        ];
        $url = Db::name('image')->where('id',$res['topic_img_id'])->find();
        $data['url'] = $url['url'];
        return $data;
    }

    /**
     * @desc 插入新的产品分类
     * @param array|mixed $data
     * @return int|string
     */
    public function insert($data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['created_time'] = date('Y-m-d H:i:s');
        $res = Db::name('category')->insert($data);
        return $res;
    }

    /**
     * @desc 更新category
     * @param $id
     * @param $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateCategory($id,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('category')->where('id',$id)->update($data);
        return $res;
    }

    /**
     * @desc 产品下的分类
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ProductandCategory()
    {
        $res = Db::name('category')->where('id','>',0)->where('type',1)->select();
        return $res;
    }
}