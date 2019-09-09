<?php
/**
 * @author Davi.
 * @desc
 * @date  2018/2/2 0002
 * @return
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use think\Exception;
use think\Request;


class index extends Common
{

    public function _initialize()
    {

    }

    /**
     * @desc 后台首页
     * @return mixed
     */
    public function index()
    {
        $admin_user = Session::get('admin_id');
        $this->assign('admin_name',$admin_user);
        return $this->fetch();
    }


}