<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
/**
 * 后台基础类库
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{


    /**
     * 初始化的方法
     */
    public function _initialize() {
        // 判定用户是否登录
        $isLogin = $this->isLogin();
        if(!$isLogin) {
            return $this->redirect('/admin/index/login');
        }

    }

    /**
     * 判定是否登录
     * @return bool
     */
    public function isLogin() {
        //获取session
        if (!Session::has('admin_id')){
            return false;
        }else{
            return true;
        }
    }
}
