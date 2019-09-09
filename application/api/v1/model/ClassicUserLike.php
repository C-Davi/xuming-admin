<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/6/13
 * Time: 16:55
 */

namespace app\v1\model;


use think\Model;

class ClassicUserLike extends Model
{
    protected $table = 'classic_user_liked';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}