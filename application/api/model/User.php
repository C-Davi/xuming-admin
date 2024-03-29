<?php

namespace app\api\model;

use think\Model;

class User extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime ='create_time';
    protected $updateTime = 'update_time';

    public function orders()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    /**
     * 用户是否存在
     * 存在返回uid，不存在返回0
     */
    public static function getByOpenID($openid)
    {
        $user = User::where('openid', '=', $openid)
            ->find();
        return $user;
    }

    public static function getById($id)
    {
        $user = User::get($id);
        return $user;
    }
}
