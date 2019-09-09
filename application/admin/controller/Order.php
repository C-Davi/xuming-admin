<?php


namespace app\admin\controller;


use think\Request;

class Order extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->order = model('NewOrder');
    }

    /**
     * @desc 全部订单
     * @return mixed
     */
    public function order()
    {
        $order = $this->order->getOrder();
        $this->assign('count',count($order));
        $this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * @desc 待支付订单
     * @return mixed
     */
    public function no_pay_order()
    {
        $where = [
            'total_count'=> ['>',0],
            'status'=>['=',1]
        ];
        $order = $this->order->getStatusOrder($where);
        $this->assign('count',count($order));
        $this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * @desc 已支付订单
     * @return mixed
     */
    public function pay_order()
    {
        $where = [
            'total_count'=> ['>',0],
            'status'=>['=',2]
        ];
        $order = $this->order->getStatusOrder($where);
//        halt($order);die();
        $this->assign('count',count($order));
        $this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * @desc 已发货订单
     * @return mixed
     */
    public function send_product_order()
    {
        $where = [
            'total_count'=> ['>',0],
            'status'=>['=',3]
        ];
        $order = $this->order->getStatusOrder($where);
        $this->assign('count',count($order));
        $this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * @desc 已支付无货订单
     * @return mixed
     */
    public function no_stock_order()
    {
        $where = [
            'total_count'=> ['>',0],
            'status'=>['=',4]
        ];
        $order = $this->order->getStatusOrder($where);
        $this->assign('count',count($order));
        $this->assign('order',$order);
        return $this->fetch();
    }
}