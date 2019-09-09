<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 11:28
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class NewProduct extends Model
{
    protected $autoWriteTimestamp = true;

    public function count()
    {
        $res = Db::name('product')->where('stock','<>',0)->count();
        return $res;
    }
    /**
     * @desc 产品列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getProductInfo()
    {
        $res = Db::view('product','id,name,price,stock,category_id,from,img_id,create_time,update_time,is_groupon,groupon_end_time,groupon_price,is_distribution')
            ->view('image','url','product.img_id = image.id')
            ->where('product.stock','<>',0)
            ->select();
        return $res;
    }

    /**
     * @desc 新增产品
     * @param array|mixed $data
     * @return int|string
     */
    public function insert($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('product')->insertGetId($data);
        return $res;
    }

    /**
     * @desc 新增主题产品
     * @param $data
     * @return int|string
     */
    public function insertTheme($data)
    {
        $res = Db::name('theme_product')->insert($data);
        return $res;
    }

    /**
     * @desc 获得指定产品信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getProduct($id)
    {
        $res = Db::view('product','id,name,price,stock,category_id,main_img_url,from,img_id,is_groupon,groupon_end_time,groupon_price,is_distribution')
            ->view('theme_product','theme_id','product.id = theme_product.product_id')
            ->where('id',$id)->find();
        return $res;
    }

    /**
     * @desc 获取查询条件下的产品
     * @param $condition array [''=>'']
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getConditionProduct($condition)
    {
        $res =  Db::view('product','id,name,price,stock,category_id,from,img_id,create_time,update_time')
            ->view('image','url','product.img_id = image.id')
            ->where($condition)->select();
        return $res;
    }

    /**
     * @desc 更新产品
     * @param $id
     * @param $data
     * @return int|string
     */
    public function updateProduct($id,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('product')->where('id',$id)->update($data);
        return $res;
    }

    /**
     * @desc 更新产品主题表数据
     * @param $product_id
     * @param $data
     * @return int|string
     */
    public function updateProductAndTheme($product_id,$data)
    {
        $res = Db::name('theme_product')->where('product_id',$product_id)->update($data);
        return $res;
    }
}