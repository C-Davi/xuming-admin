<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 10:35
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use think\Exception;
use think\Request;

/**
 * @desc 公共检测
 * Class Common
 * @package app\admin\controller
 */
class Common extends Controller
{
    public function _initialize()
    {
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