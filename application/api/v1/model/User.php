<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/6/3
 * Time: 16:28
 */

namespace app\v1\model;


use think\Model;

class User extends Model
{
    protected $table = 'user';

    public static function getByOpenID($openid)
    {
        $user = self::where('open_id', '=', $openid)
            ->find();
        return $user;
    }
}