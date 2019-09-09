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

class NewAdmin extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @desc 获取管理员信息
     * @param $tel
     * @return array|false|\PDOStatement|string|Model
     */
    public function getUser($id)
    {
        $res = Db::name('third_app')->where('app_id',$id)->find();
        unset($res['id']);
        return $res;
    }
}