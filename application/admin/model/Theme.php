<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17
 * Time: 22:39
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class Theme extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @desc 获取所有的theme
     * @return int|string
     */
    public function getTheme()
    {
        $res = Db::name('theme')->count();
        return $res;
    }

    /**
     *
     * @param array|mixed $data
     * @return int|string
     */
    public function insert($data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['created_time'] = date('Y-m-d H:i:s');
        $res = Db::name('theme')->insert($data);
        return $res;
    }

    /**
     * @desc 修改theme （包涵状态）
     * @param $id
     * @param $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updated($id,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('theme')->where('id',$id)->update($data);
        return $res;
    }

    /**
     * @desc 获取theme的列的信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemeInfo()
    {
        $res =Db::view('theme','id,name,description,topic_img_id,head_img_id,created_time,update_time')
            ->view('image','url','theme.topic_img_id = image.id')
            ->where('theme.id','<>',0)
            ->select();
        return $res;
    }

    /**
     * @desc 获取theme的列表信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemeInfoToArray()
    {
        return Db::view('theme','id,name,description,topic_img_id,head_img_id,created_time,update_time,type')
            ->view('image','url','theme.topic_img_id = image.id')
            ->where('theme.id','<>',0)
            ->select()->toArray();
    }

    /**
     * @desc 获得theme的
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemeTopicImg()
    {
        $res = Db::view('theme','id,topic_img_id')
            ->view('image','url','theme.topic_img_id = image.id')
            ->where('theme.id','<>',0)
            ->select();
        return $res;
    }

    /**
     * @desc 得到theme图片地址
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemeImg($id)
    {
        return Db::name('image')->where('id',$id)->find();
    }

    /**
     * @desc 获得某一个theme的信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemInfo($id)
    {
        $res = Db::view('theme')
            ->view('image','url','theme.topic_img_id = image.id')
            ->where('theme.id','=',$id)
            ->find();
        return $res;
    }

    /**
     * @desc 更新theme
     * @param $id
     * @param $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateThemeImg($id,$data)
    {
        $res = Db::name('theme')->where('id',$id)->update($data);
        return $res;
    }

    /**
     * @desc 产品下获取所有的theme
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ProductandTheme()
    {
        $res = Db::name('theme')->where('id','<>',0)->where('type','=',1)->select();
        return $res;
    }
}