<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 22:53
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class NewOrder extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrder()
    {
        $res = Db::name('order')->where('total_count','>',0)
            ->order('update_time','desc')->select();
        return $res;
    }

    public function getStatusOrder($where)
    {
        $res = Db::name('order')->where($where)
            ->order('update_time','desc')->select();
        return $res;
    }
    public function count()
    {
        $res = Db::name('order')->where('total_count','>',0)->count();
        return $res;
    }
}