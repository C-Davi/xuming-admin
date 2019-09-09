<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/16
 * Time: 14:31
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class Image extends Model
{
    protected $autoWriteTimestamp = true;
    /**
     * @desc 插入图片
     * @return int|string|void
     */
    public function insert($data)
    {
        $res = Db::name('image')->insertGetId($data);
        return $res;
    }

    /**
     * @desc 更新图片
     * @param $id
     * @param $data
     * @return int|string
     */
    public function imgupdate($id,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('image')->where('id',$id)->update($data);
        return $res;
    }
}