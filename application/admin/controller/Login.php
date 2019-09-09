<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;


class Login extends Common
{
    private $obj;
    public function _initialize() {
        $this->obj = model('NewAdmin');
    }

    /**
     * @desc 登录页
     * @return mixed|void
     */
    public function login()
    {
        $isLogin = $this->isLogin();
        if($isLogin) {
            return $this->redirect('admin/index/index');
        }else {
            // 如果后台用户已经登录了， 那么我们需要跳到后台页面
            return $this->fetch();
        }
    }

    /**
     * 登录相关业务
     */
    public function check() {
        if (!request()->isPost()){
            return false;
        }
        $param = input('param.');
        $result = $this->obj->getUser($param['id']);
        if ($result['app_id'] == $param['id'] && $result['app_secret'] == $param['pwd']){
            Session::set('admin_id',$result['app_id']);
            $this->redirect('admin/index/index');
        }else{
            $this->redirect('admin/index/login');
        }
    }

    /**
     * 退出登录的逻辑
     * 1、清空session
     * 2、 跳转到登录页面
     */
    public function logout() {
        Session::delete('admin_id');
        $this->redirect('admin/index/login');
    }
}
