<?php
/**
 * Created by PhpStorm.
 * User: Davi
 * Date: 2018/3/15
 * Time: 16:19
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class Order extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @desc生成预支付订单
     * @return int|string
     */
    public function createorder($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $res = Db::name('ex_order')->insert($data);
        return $res;
    }

    /**
     * @desc 微信支付回调更新订单
     * @param $order_no
     * @param $data
     * @return int|string
     */
    public function updateorder($order_no,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = Db::name('ex_order')->where('order_no', $order_no)->update($data);
        return $res;
    }

    /**
     * @desc 获得订单状态，1未支付，2已支付，3已发货
     */
    public function getorderstatus($orderno)
    {
        $res = Db::name('ex_order')->where('order_no',$orderno)->find();
        return $res;
    }


    /**
     * @desc 查找用户支付订单
     * @param $uid
     * @return array|false|\PDOStatement|string|Model
     */
    public function uidgetorder($uid)
    {
        $res = Db::name('ex_order')->where('uid',$uid)->where('status',2)->select();
        return $res;
    }

    /**
     * @desc
     * @param $uid
     * @return array|false|\PDOStatement|string|Model
     */
    public function uidgetorderstatus($uid)
    {
        $res = Db::name('ex_order')->where('uid',$uid)->where('status',2)->find();
        return $res;
    }

    /**
     * @desc 根据用户id查找已支付课程
     * @param $uid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function uidgetclass($uid)
    {
        $res = Db::name('ex_order')->field('class_name')->where('uid',$uid)->where('status',2)->select();
        return $res;
    }


    /**
     * @desc 获得全部支付订单
     * @param int $status
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getallorderuinfo($status = 2)
    {
        $res = Db::view('ex_order','order_no,uid,classno,status,class_amount,create_time')
        ->view('ex_user','uid,nick,age,sex,tel,can_get_tel','ex_user.uid=ex_order.uid')
        ->order('ex_order.create_time asc')
        ->where('ex_order.status', '=', $status)
        ->select();
        return $res;
    }

    /**
     * @desc 获得全部积木活动支付订单
     * @param int $status
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getjimuallorderuinfo($status = 2)
    {
        $res = Db::view('ex_order','order_no,uid,classno,status,class_amount,create_time')
            ->view('ex_user','uid,nick,age,sex,tel,can_get_tel','ex_user.uid=ex_order.uid')
            ->order('ex_order.create_time asc')
            ->where('ex_order.status', '=', $status)
            ->where('ex_order.classno', '=', '乌克兰机械木制玩具拼搭大赛第三期')
            ->select();
        return $res;
    }

    /**
     * @desc 获得全部支付订单
     * @param int $status
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getneworderuinfo($status = 2)
    {
        $res = Db::view('ex_order','order_no,uid,classno,status,class_amount,create_time')
            ->view('ex_user','uid,nick,age,sex,tel,can_get_tel','ex_user.uid=ex_order.uid')
            ->order('ex_order.create_time desc')
            ->where('ex_order.status', '=', $status)
            ->where('create_time', '>', '2018-03-30 00:00:00')
            ->select();
        return $res;
    }

    /**
     * @desc 购买邮寄订单
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getbookstoreorderinfo()
    {
        $res = Db::view('ex_porder','order_no,money,status,is_baoyou,is_category,openid,create_time')
            ->view('ex_wechat_user','uid,openid','ex_porder.openid=ex_wechat_user.openid')
            ->view('ex_user_address','uid,nick,tel,address,code,is_first','ex_wechat_user.uid=ex_user_address.uid')
            ->view('ex_order_product','order_id,p_name,p_amount,status','ex_order_product.order_id=ex_porder.order_no')
            ->order('ex_porder.create_time desc')
            ->where('ex_porder.status','=',2)
            ->where('ex_porder.is_category','=',2)
            ->where('ex_porder.is_baoyou','neq',3)
            ->select();
        return $res;
    }

    /**
     * @desc 购买到店自取订单
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function orderlistinvite()
    {
        $res = Db::view('ex_porder','order_no,money,status,is_baoyou,is_category,openid,create_time')
            ->view('ex_wechat_user','uid,openid','ex_porder.openid=ex_wechat_user.openid')
            ->view('ex_user_address','uid,nick,tel,address,code,is_first','ex_wechat_user.uid=ex_user_address.uid')
            ->view('ex_order_product','order_id,p_name,p_amount,money,status','ex_order_product.order_id=ex_porder.order_no')
            ->order('ex_porder.create_time desc')
            ->where('ex_porder.status','=',2)
            ->where('ex_porder.is_category','=',2)
            ->where('ex_porder.is_baoyou','=',3)
            ->where('ex_user_address.is_first','=',1)
            ->select();
        return $res;
    }

    /**
     * @desc 购物车邮寄订单
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function categoryorder()
    {
        $res = Db::view('ex_porder','order_no,money,status,is_baoyou,is_category,create_time')
            ->view('ex_category_user','uid,order_no,p_name,num,status,money','ex_porder.order_no=ex_category_user.order_no')
            ->view('ex_user_address','uid,nick,tel,address,code,is_first','ex_category_user.uid=ex_user_address.uid')
            ->order('ex_porder.create_time desc')
            ->where('ex_porder.status','=',2)
            ->where('ex_porder.is_category','=',1)
            ->where('ex_porder.is_baoyou','neq',3)
            ->where('ex_user_address.is_first','=',1)
            ->select();
        return $res;
    }

    /**
     * @desc 购物车自取订单
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function categoryinvite()
    {
        $res = Db::view('ex_porder','order_no,money,status,is_baoyou,is_category,create_time')
            ->view('ex_category_user','uid,order_no,p_name,num,status,money','ex_porder.order_no=ex_category_user.order_no')
            ->view('ex_user_address','uid,nick,tel,address,code,is_first','ex_category_user.uid=ex_user_address.uid')
            ->order('ex_porder.create_time desc')
            ->where('ex_porder.status','=',2)
            ->where('ex_porder.is_category','=',1)
            ->where('ex_porder.is_baoyou','=',3)
            ->where('ex_user_address.is_first','=',1)
            ->select();
        return $res;
    }

    /**
     * @desc 订单状态修改
     * @param $orderno
     * @param $data
     * @return int|string
     */
    public function updatestoreorder($orderno,$data)
    {
        $res = Db::name('ex_porder')->where('order_no',$orderno)->update($data);
        return $res;
    }

    /**
     * @desc 购物车订单状态修改
     * @param $orderno
     * @param $data
     * @return int|string
     */
    public function updatecategoryorder($orderno,$data)
    {
        $res = Db::name('ex_category_user')->where('order_no',$orderno)->update($data);
        return $res;
    }
}