<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/6/3
 * Time: 16:18
 */

namespace app\v1\service;

use think\Exception;
use app\v1\model\User as UserModel;
class UserToken
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(
            config('wx.login_url'),
            $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }
        else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            }
            else {
                return $wxResult;
            }
        }
    }

    public function checkOpenId($openId,$userInfo){
        $user = UserModel::getByOpenID($openId);
        if ($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openId,$userInfo);
        }
        $cachedValue = $openId.$uid;
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function grantToken($wxResult){
        // 拿到openid
        // 数据库里看一下，这个openid是不是已经存在
        // 如果存在 则不处理，如果不存在那么新增一条user记录
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端去
        // key: 令牌
        // value: wxResult，uid, scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }
        else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = config('secure.token_salt');
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');

        $request = cache($key, $value, $expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function newUser($openid,$userInfo){
        $user = UserModel::create([
            'open_id' => $openid,
            'nick'   => $userInfo['nickName'],
            'icon'=>$userInfo['avatarUrl'],
            'gender'=> $userInfo['gender'],
            'city'=> $userInfo['city'],
            'province'=>$userInfo['province'],
            'country'=> $userInfo['country'],
            'created_time'=>date('Y-m-d H:i:s'),
            'updated_time'=>date('Y-m-d H:i:s')
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException(
            [
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }
}