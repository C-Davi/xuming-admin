<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/6/3
 * Time: 15:48
 */

namespace app\v1\controller;


use think\Controller;
use think\Request;
use app\v1\service\UserToken as UserService;

class Base extends Controller
{
    public function getToken($code){
        $user = new UserService($code);
        $openId = $user->get();
        return show(1,'success',$openId);
    }

    public function CheckToken(){
        $params = input('param.');
        $code = $params['code'];
        $openid = $params['openid'];
        $userInfo = json_decode($params['userInfo']);
        $user = [
            'nickName' => base64_encode($userInfo->nickName),
            'avatarUrl'=> $userInfo->avatarUrl,
            'gender'   => $userInfo->gender,
            'city'     => $userInfo->city,
            'province' => $userInfo->province,
            'country'  => $userInfo->country
        ];
        $result = new UserService($code);
        $res = $result->checkOpenId($openid,$user);
        if ($res){
            return show(1,'success',$res);
        }else{
            return show(0,'fail','');
        }
    }
}